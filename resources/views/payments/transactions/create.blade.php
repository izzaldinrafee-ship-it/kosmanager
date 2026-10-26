
@extends('layouts.app')

@section('title', 'Catat Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('payments.transactions.index', $payment) }}"
            class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Transaksi
        </a>

        <h2 class="fw-bold mt-3 mb-1">Catat Pembayaran</h2>
        <p class="text-secondary mb-0">
            Masukkan pembayaran yang diterima dari penghuni kos.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Ada data yang perlu diperbaiki:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Tagihan</h5>

                    <div class="mb-3">
                        <div class="text-secondary small">Nama Penghuni</div>
                        <div class="fw-semibold">
                            {{ $payment->tenant->name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-secondary small">Nomor Kamar</div>
                        <div class="fw-semibold">
                            {{ $payment->tenant->room->room_number ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-secondary small">Periode Tagihan</div>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::createFromDate(
                                $payment->billing_year,
                                $payment->billing_month,
                                1
                            )->translatedFormat('F Y') }}
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Total Tagihan</span>
                        <span class="fw-semibold">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Sudah Dibayar</span>
                        <span class="fw-semibold text-success">
                            Rp{{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-semibold">Sisa Tagihan</span>
                        <span class="fs-5 fw-bold text-danger">
                            Rp{{ number_format($remainingAmount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Detail Pembayaran</h5>

                    @if ($remainingAmount <= 0)
                        <div class="alert alert-success mb-0">
                            Tagihan ini sudah lunas. Tidak ada pembayaran tambahan yang perlu dicatat.
                        </div>
                    @else
                        <form
                            action="{{ route('payments.transactions.store', $payment) }}"
                            method="POST"
                        >
                            @csrf

                            <div class="mb-3">
                                <label for="amount" class="form-label fw-semibold">
                                    Nominal Pembayaran (Rp)
                                </label>
                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    min="1"
                                    max="{{ $remainingAmount }}"
                                    step="1"
                                    value="{{ old('amount') }}"
                                    placeholder="Contoh: 300000"
                                    required
                                >
                                <div class="form-text">
                                    Maksimal Rp{{ number_format($remainingAmount, 0, ',', '.') }}.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="transaction_date" class="form-label fw-semibold">
                                    Tanggal Pembayaran
                                </label>
                                <input
                                    type="date"
                                    name="transaction_date"
                                    id="transaction_date"
                                    class="form-control"
                                    value="{{ old('transaction_date', date('Y-m-d')) }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label fw-semibold">
                                    Metode Pembayaran
                                </label>
                                <select
                                    name="payment_method"
                                    id="payment_method"
                                    class="form-select"
                                    required
                                >
                                    <option value="Tunai" @selected(old('payment_method', 'Tunai') === 'Tunai')>
                                        Tunai
                                    </option>
                                    <option value="Transfer" @selected(old('payment_method') === 'Transfer')>
                                        Transfer Bank
                                    </option>
                                    <option value="QRIS" @selected(old('payment_method') === 'QRIS')>
                                        QRIS
                                    </option>
                                    <option value="E-Wallet" @selected(old('payment_method') === 'E-Wallet')>
                                        E-Wallet
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold">
                                    Catatan <span class="text-secondary fw-normal">(Opsional)</span>
                                </label>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Contoh: Pembayaran melalui transfer bank"
                                >{{ old('notes') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a
                                    href="{{ route('payments.transactions.index', $payment) }}"
                                    class="btn btn-light border"
                                >
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Simpan Pembayaran
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
