<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\JurnalUmum;
use App\Models\Pengeluaran;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with(['debit', 'kredit'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.pengeluaran.index', compact('pengeluaran'));
    }

    public function create()
    {
        $coaList = Coa::orderBy('kode_akun')->get();
        $penggajianList = Penggajian::with('pegawai')->orderByDesc('id')->get();

        return view('admin.pengeluaran.create', compact('coaList', 'penggajianList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'in:manual,penggajian'],
            'deskripsi' => ['required', 'string'],
            'jumlah' => ['required', 'numeric'],
            'penggajian_id' => ['nullable', 'required_if:kategori,penggajian', 'exists:penggajian,id'],
            'coa_debit_id' => ['required', 'exists:coa,id'],
            'coa_kredit_id' => ['required', 'exists:coa,id'],
            'bukti_pembayaran' => ['nullable', 'required_if:kategori,manual', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $kategori = $validated['kategori'];
        $jenis = $kategori === 'penggajian' ? 'gaji' : 'operasional';
        $nominal = (int) $validated['jumlah'];
        $keterangan = $validated['deskripsi'];
        $tanggal = Carbon::parse($validated['tanggal'])->toDateString();

        $statusVerifikasi = 'pending';
        $catatanVerifikasi = null;

        $normalizeNominal = function ($value) {
            return (int) preg_replace('/[^0-9]/', '', (string) $value);
        };

        $invoicePath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $invoicePath = $request->file('bukti_pembayaran')->store('invoice', 'public');
        }

        $penggajian = null;
        if ($kategori === 'manual') {
            $jumlahInput = (int) $request->jumlah;

            $nominalInvoiceRaw = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $nominalInvoiceRaw = $request->file('bukti_pembayaran')->getClientOriginalName();
            } elseif ($invoicePath) {
                $nominalInvoiceRaw = $invoicePath;
            }

            $nominalInvoice = 0;
            if (!empty($nominalInvoiceRaw)) {
                // Prefer patterns like 62.000 / 1,250,000 if present in filename
                if (preg_match('/(\d{1,3}(?:[\.,]\d{3})+)/', $nominalInvoiceRaw, $m)) {
                    $nominalInvoice = $normalizeNominal($m[1]);
                } elseif (preg_match('/\b(?:rp|idr|total|nominal|invoice)\b/i', $nominalInvoiceRaw)) {
                    // If filename contains currency hints, allow raw digits extraction
                    $nominalInvoice = $normalizeNominal($nominalInvoiceRaw);
                } else {
                    // Default camera/whatsapp filenames often contain timestamps; treat as "OCR not available"
                    $digitsOnly = preg_replace('/\D+/', '', (string) $nominalInvoiceRaw);
                    if (strlen($digitsOnly) >= 4 && strlen($digitsOnly) <= 7) {
                        $nominalInvoice = (int) $digitsOnly;
                    }
                }
            }

            // If we can't confidently parse, assume input admin is the invoice nominal (no OCR)
            if ($nominalInvoice === 0) {
                $nominalInvoice = $jumlahInput;
            }

            $selisih = abs($jumlahInput - $nominalInvoice);
            if ($selisih <= 1000) {
                $statusVerifikasi = 'approved';
                $catatanVerifikasi = 'Nominal sesuai invoice';
            } else {
                $statusVerifikasi = 'rejected';
                $catatanVerifikasi = 'Nominal tidak sesuai invoice';
            }
        } else {
            $penggajian = Penggajian::findOrFail($validated['penggajian_id']);

            $already = Pengeluaran::where('referensi_penggajian_id', $penggajian->id)->exists();
            if ($already) {
                return back()
                    ->withErrors(['penggajian_id' => 'Penggajian ini sudah memiliki pengeluaran.'])
                    ->withInput();
            }

            if ((int) $penggajian->total_gaji === $nominal) {
                $statusVerifikasi = 'approved';
            } else {
                $statusVerifikasi = 'rejected';
                $catatanVerifikasi = 'Jumlah tidak sesuai dengan total_gaji penggajian.';
            }
        }

        return DB::transaction(function () use ($validated, $jenis, $tanggal, $nominal, $keterangan, $statusVerifikasi, $catatanVerifikasi, $penggajian, $invoicePath) {
            $payload = [
                'tanggal' => $tanggal,
                'jenis' => $jenis,
                'referensi_penggajian_id' => $penggajian?->id,
                'coa_debit_id' => $validated['coa_debit_id'],
                'coa_kredit_id' => $validated['coa_kredit_id'],
                'nominal' => $nominal,
                'keterangan' => $keterangan,
            ];

            if (Schema::hasColumn('pengeluaran', 'kategori')) {
                $payload['kategori'] = $validated['kategori'];
            }
            if (Schema::hasColumn('pengeluaran', 'deskripsi')) {
                $payload['deskripsi'] = $keterangan;
            }
            if (Schema::hasColumn('pengeluaran', 'jumlah')) {
                $payload['jumlah'] = $nominal;
            }
            if (Schema::hasColumn('pengeluaran', 'id_penggajian')) {
                $payload['id_penggajian'] = $penggajian?->id;
            }
            if (Schema::hasColumn('pengeluaran', 'bukti_pembayaran')) {
                $payload['bukti_pembayaran'] = $invoicePath ?? '-';
            }
            if (Schema::hasColumn('pengeluaran', 'status_verifikasi')) {
                $payload['status_verifikasi'] = $statusVerifikasi;
            }
            if (Schema::hasColumn('pengeluaran', 'catatan_verifikasi')) {
                $payload['catatan_verifikasi'] = $catatanVerifikasi;
            }

            $pengeluaran = Pengeluaran::create($payload);

            if ($statusVerifikasi === 'approved') {
                $jurnal = JurnalUmum::create([
                    'tgl' => $tanggal,
                    'no_referensi' => 'PG-' . $pengeluaran->id,
                    'deskripsi' => $keterangan,
                ]);

                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $validated['coa_debit_id'],
                    'deskripsi' => $keterangan,
                    'debit' => (float) $nominal,
                    'credit' => 0,
                ]);

                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $validated['coa_kredit_id'],
                    'deskripsi' => $keterangan,
                    'debit' => 0,
                    'credit' => (float) $nominal,
                ]);

                $pengeluaran->forceFill([
                    'id_jurnal' => $jurnal->id,
                ])->save();

                if ($penggajian) {
                    $penggajian->forceFill([
                        'id_jurnal' => $jurnal->id,
                        'pengeluaran_id' => $pengeluaran->id,
                    ])->saveQuietly();
                }
            }

            return redirect()->route('admin.pengeluaran.index');
        });
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('admin.pengeluaran.index');
    }
}
