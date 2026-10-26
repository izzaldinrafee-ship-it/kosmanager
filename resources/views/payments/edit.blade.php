
@extends('layouts.app')

@section('title', 'Edit Tagihan')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <a href="{{ route('payments.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke pembayaran
        </a>

        <h2 class="fw-bold mt-3 mb-1">Edit Tagihan</h2>
        <p class="text-secondary mb-0">
            Perbarui nominal tagihan, jatuh tempo, dan catatan pembayaran.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">Periksa kembali data berikut:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="bg-light rounded p-3 mb-4">
                <div class="fw-bold">{{ $payment->tenant->name }}</div>

                <div class="text-secondary">
                    Kamar {{ $payment->tenant->room->room_number }}
                    ·
                    {{ \Carbon\Carbon::createFromDate(
                        $payment->billing_year,
                        $payment->billing_month,
                        1
                    )->translatedFormat('F Y') }}
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-secondary mb-1">Total Tagihan</div>
                        <div class="fw-bold fs-5">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-secondary mb-1">Sudah Dibayar</div>
                        <div class="fw-bold fs-5 text-success">
                            Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-secondary mb-1">Sisa Tagihan</div>
                        <div class="fw-bold fs-5 text-danger">
                            Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label">
                            Nominal Tagihan (Rp)
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            min="{{ max(1, $payment->paid_amount) }}"
                            step="1"
                            value="{{ old('amount', $payment->amount) }}"
                            required
                        >

                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-secondary">
                            Nominal tidak boleh lebih kecil daripada jumlah yang sudah dibayar.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label for="due_date" class="form-label">
                            Tanggal Jatuh Tempo
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            class="form-control @error('due_date') is-invalid @enderror"
                            value="{{ old('due_date', $payment->due_date->format('Y-m-d')) }}"
                            required
                        >

                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">
                            Catatan (opsional)
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            maxlength="1000"
                            class="form-control @error('notes') is-invalid @enderror"
                        >{{ old('notes', $payment->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="alert alert-info mt-4 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Untuk mencatat pembayaran baru atau cicilan, gunakan fitur
                    <strong>Catat Pembayaran</strong> agar riwayat transaksi dan
                    status tagihan tetap konsisten.
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('payments.index') }}" class="btn btn-light">
                        Batal
                    </a>

                    <a
                        href="{{ route('payments.transactions.index', $payment) }}"
                        class="btn btn-outline-success ms-md-auto"
                    >
                        <i class="bi bi-clock-history me-1"></i>
                        Riwayat Transaksi
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
