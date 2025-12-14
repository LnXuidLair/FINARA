@extends('parent')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">Pembayaran Tagihan</h4>
        <a href="{{ route('parent.payment.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <div class="text-muted">Total tagihan belum dibayar</div>
                    <div class="fw-semibold fs-4">Rp {{ number_format($totalUnpaid ?? 0, 0, ',', '.') }}</div>
                </div>

                <button type="button" class="btn btn-primary" id="btnBayarTagihan" {{ ($totalUnpaid ?? 0) > 0 ? '' : 'disabled' }}>
                    <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                </button>
            </div>

            <div class="text-muted mb-3">Pembayaran akan otomatis mengambil total tagihan yang belum dibayar. Nominal tidak bisa diubah.</div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Jenis Tagihan</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = 0;
                        @endphp
                        @foreach(($tagihanBySiswa ?? collect()) as $siswaId => $items)
                            @foreach($items as $t)
                                @php $rows++; @endphp
                                <tr>
                                    <td>{{ $t->siswa->nama_siswa ?? '-' }}</td>
                                    <td>{{ $t->jenis_tagihan }}</td>
                                    <td>{{ $t->periode }}</td>
                                    <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if($rows === 0)
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada tagihan yang perlu dibayar.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('btnBayarTagihan');
            if (!btn) return;

            btn.addEventListener('click', function () {
                btn.disabled = true;

                fetch('{{ route('create-tagihan-payment') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                })
                .then(async (r) => {
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok) throw new Error(data.error || 'Gagal membuat pembayaran tagihan.');
                    return data;
                })
                .then((data) => {
                    if (!data.token) throw new Error('Token Midtrans tidak tersedia.');
                    window.snap.pay(data.token, {
                        onSuccess: function () { window.location.href = '{{ route('parent.payment.index') }}'; },
                        onPending: function () { window.location.href = '{{ route('parent.payment.index') }}'; },
                        onError: function () { alert('Pembayaran gagal'); btn.disabled = false; },
                        onClose: function () { btn.disabled = false; }
                    });
                })
                .catch((e) => {
                    alert(e.message || 'Terjadi kesalahan');
                    btn.disabled = false;
                });
            });
        });
    </script>
@endsection
