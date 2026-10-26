
@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a href="{{ route('payments.index') }}"
                class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pembayaran
            </a>

            <h2 class="fw-bold mt-3 mb-1">Riwayat Transaksi</h2>
            <p class="text-secondary mb-0">
                Catatan pembayaran untuk tagihan penghuni kos.
            </p>
        </div>

        @if ($payment->paid_amount < $payment->amount)
            <a href="{{ route('payments.transactions.create', $payment) }}"
                class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Catat Pembayaran
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="text-secondary small mb-1">Nama Penghuni</div>
                    <div class="fw-bold">{{ $payment->tenant->name }}</div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="text-secondary small mb-1">Kamar</div>
                    <div class="fw-bold">
                        {{ $payment->tenant->room->room_number ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="text-secondary small mb-1">Periode Tagihan</div>
                    <div class="fw-bold">
                        {{ \Carbon\Carbon::createFromDate(
                            $payment->billing_year,
                            $payment->billing_month,
                            1
                        )->translatedFormat('F Y') }}
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="text-secondary small mb-1">Status</div>
                    @if ($payment->status === 'Lunas')
                        <span class="badge bg-success">Lunas</span>
                    @elseif ($payment->status === 'Sebagian')
                        <span class="badge bg-warning text-dark">Dibayar Sebagian</span>
                    @else
                        <span class="badge bg-danger">Belum Bayar</span>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="text-secondary small mb-1">Total Tagihan</div>
                        <div class="fs-5 fw-bold">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="text-secondary small mb-1">Total Dibayar</div>
                        <div class="fs-5 fw-bold text-success">
                            Rp{{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="text-secondary small mb-1">Sisa Tagihan</div>
                        <div class="fs-5 fw-bold text-danger">
                            Rp{{ number_format(
                                max(0, $payment->amount - $payment->paid_amount),
                                0,
                                ',',
                                '.'
                            ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-1">Daftar Transaksi</h5>
                <p class="text-secondary small mb-0">
                    Setiap baris menunjukkan pembayaran yang benar-benar dicatat.
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Catatan</th>
                            <th class="text-end pe-4">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td class="ps-4">
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if ($transaction->payment_method === 'Tunai')
                                        <span class="badge bg-success-subtle text-success">
                                            Tunai
                                        </span>
                                    @elseif ($transaction->payment_method === 'Transfer')
                                        <span class="badge bg-primary-subtle text-primary">
                                            Transfer
                                        </span>
                                    @elseif ($transaction->payment_method === 'QRIS')
                                        <span class="badge bg-info-subtle text-info-emphasis">
                                            QRIS
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $transaction->payment_method }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $transaction->notes ?: '-' }}
                                </td>
                                <td class="text-end pe-4 fw-semibold">
                                    Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-receipt fs-1 text-secondary"></i>
                                    <p class="fw-semibold mt-3 mb-1">
                                        Belum ada transaksi
                                    </p>
                                    <p class="text-secondary small mb-0">
                                        Catat pembayaran untuk mulai membuat riwayat transaksi.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="p-3 border-top">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
