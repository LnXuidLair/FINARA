@extends('parent')

@section('content')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<div class="parent-dashboard">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0 fs-3 fw-semibold text-reset">Pembayaran Tagihan</h3>
    </div>

    @if($siswaList->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada data siswa yang terhubung dengan akun Anda.
        </div>
    @else
        @foreach($siswaList as $siswa)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $siswa->nama_siswa }} ({{ $siswa->kelas }})</h5>
                </div>
                <div class="card-body">
                    @php
                        $siswaTagihan = $tagihanList->where('siswa_id', $siswa->id);
                    @endphp
                    
                    @if($siswaTagihan->isEmpty())
                        <div class="text-muted">Tidak ada tagihan yang perlu dibayar.</div>
                    @else
                        @foreach($siswaTagihan as $tagihan)
                            <div class="border rounded p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $tagihan->jenis_tagihan }}</h6>
                                        <small class="text-muted">{{ $tagihan->periode ?? '-' }}</small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="fw-bold">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</div>
                                        @if($tagihan->sisa > 0)
                                            <small class="text-warning">Sisa: Rp {{ number_format($tagihan->sisa, 0, ',', '.') }}</small>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        @if($tagihan->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <button class="btn btn-primary btn-sm" onclick="bayarTagihan({{ $tagihan->id }})">
                                                <i class="fas fa-credit-card"></i> Bayar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
function bayarTagihan(tagihanId) {
    if (confirm('Apakah Anda yakin ingin membayar tagihan ini?')) {
        // Load Midtrans Snap
        fetch('/create-tagihan-payment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'tagihan_id': tagihanId
            })
        })
        .then(async (r) => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) {
                throw new Error(data.error || 'Gagal membuat pembayaran tagihan.');
            }
            return data;
        })
        .then((data) => {
            if (!data.token) throw new Error('Token Midtrans tidak tersedia.');
            window.snap.pay(data.token, {
                onSuccess: function () { 
                    alert('Pembayaran berhasil!');
                    window.location.reload(); 
                },
                onPending: function () { 
                    alert('Pembayaran pending.');
                    window.location.reload(); 
                },
                onError: function () { 
                    alert('Pembayaran gagal'); 
                },
                onClose: function () { 
                    // User closed the payment window
                }
            });
        })
        .catch((e) => {
            alert(e.message || 'Terjadi kesalahan');
        });
    }
}
</script>
@endsection
