
@extends('layouts.app')

@section('title', 'Pembayaran Kos')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pembayaran Kos</h2>
            <p class="text-secondary mb-0">
                Kelola tagihan dan pantau pembayaran penghuni kos.
            </p>
        </div>

        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Buat Tagihan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan Keuangan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary">Total Tagihan</span>
                        <div class="rounded-circle bg-primary-subtle text-primary p-2">
                            <i class="bi bi-receipt fs-5"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Berdasarkan filter aktif</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary">Total Dibayar</span>
                        <div class="rounded-circle bg-success-subtle text-success p-2">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-success mb-1">
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Pembayaran yang sudah masuk</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary">Sisa Tagihan</span>
                        <div class="rounded-circle bg-danger-subtle text-danger p-2">
                            <i class="bi bi-exclamation-circle fs-5"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-danger mb-1">
                        Rp {{ number_format($totalSisa, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Tagihan yang belum lunas</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Pencarian dan Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('payments.index') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-lg-4">
                        <label for="search" class="form-label fw-semibold">
                            Cari Pembayaran
                        </label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Nama, nomor telepon, atau kamar..."
                            value="{{ $search ?? '' }}"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Belum Bayar" @selected(($status ?? '') === 'Belum Bayar')>
                                Belum Bayar
                            </option>
                            <option value="Sebagian" @selected(($status ?? '') === 'Sebagian')>
                                Sebagian
                            </option>
                            <option value="Lunas" @selected(($status ?? '') === 'Lunas')>
                                Lunas
                            </option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="month" class="form-label fw-semibold">Bulan</label>
                        <select name="month" id="month" class="form-select">
                            <option value="">Semua Bulan</option>
                            @foreach ([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ] as $number => $monthName)
                                <option
                                    value="{{ $number }}"
                                    @selected((string) ($month ?? '') === (string) $number)
                                >
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="year" class="form-label fw-semibold">Tahun</label>
                        <input
                            type="number"
                            name="year"
                            id="year"
                            class="form-control"
                            placeholder="{{ now()->year }}"
                            min="2020"
                            max="2100"
                            value="{{ $year ?? '' }}"
                        >
                    </div>

                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a
                            href="{{ route('payments.index') }}"
                            class="btn btn-outline-secondary"
                            title="Reset filter"
                            aria-label="Reset filter"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pembayaran --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="fw-bold mb-0">Daftar Pembayaran</h5>
                <span class="text-secondary small">
                    {{ $payments->total() }} data ditemukan
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 ps-md-4">No.</th>
                            <th>Penghuni</th>
                            <th>Kamar</th>
                            <th>Periode</th>
                            <th>Total Tagihan</th>
                            <th>Dibayar</th>
                            <th>Sisa</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-center pe-3 pe-md-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="ps-3 ps-md-4">
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $payment->tenant->name ?? 'Penghuni dihapus' }}
                                    </div>
                                    <small class="text-secondary">
                                        {{ $payment->tenant->phone ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $payment->tenant->room->room_number ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::createFromDate(
                                        $payment->billing_year,
                                        $payment->billing_month,
                                        1
                                    )->locale('id')->translatedFormat('F Y') }}
                                </td>

                                <td class="text-nowrap">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>

                                <td class="text-nowrap text-success">
                                    Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                                </td>

                                <td class="text-nowrap fw-semibold {{ $payment->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $payment->due_date
                                        ? \Carbon\Carbon::parse($payment->due_date)->format('d/m/Y')
                                        : '-' }}
                                </td>

                                <td>
                                    @if ($payment->status === 'Lunas')
                                        <span class="badge bg-success-subtle text-success">Lunas</span>
                                    @elseif ($payment->status === 'Sebagian')
                                        <span class="badge bg-warning-subtle text-warning-emphasis">Sebagian</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Belum Bayar</span>
                                    @endif
                                </td>

                                <td class="text-center text-nowrap pe-3 pe-md-4">
                                    <div class="d-inline-flex gap-1">
                                        {{-- Riwayat transaksi --}}
                                        <a
                                            href="{{ route('payments.transactions.index', $payment) }}"
                                            class="btn btn-sm btn-outline-success"
                                            title="Riwayat transaksi"
                                            aria-label="Riwayat transaksi"
                                        >
                                            <i class="bi bi-clock-history"></i>
                                        </a>

                                        {{-- Catat pembayaran jika masih ada sisa --}}
                                        @if ($payment->remaining_amount > 0)
                                            <a
                                                href="{{ route('payments.transactions.create', $payment) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Catat pembayaran"
                                                aria-label="Catat pembayaran"
                                            >
                                                <i class="bi bi-cash-coin"></i>
                                            </a>
                                        @endif

                                        {{-- Edit tagihan --}}
                                        <a
                                            href="{{ route('payments.edit', $payment) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Edit tagihan"
                                            aria-label="Edit tagihan"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- Hapus tagihan --}}
                                        <form
                                            action="{{ route('payments.destroy', $payment) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus tagihan ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus tagihan"
                                                aria-label="Hapus tagihan"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-secondary d-block mb-3"></i>
                                    <h6 class="fw-semibold">Data pembayaran tidak ditemukan</h6>
                                    <p class="text-secondary mb-0">
                                        Coba ubah kata kunci atau reset filter pencarian.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($payments->hasPages())
            <div class="card-footer bg-white border-0 p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <small class="text-secondary">
                        Menampilkan {{ $payments->firstItem() }}–{{ $payments->lastItem() }}
                        dari {{ $payments->total() }} data
                    </small>

                    {{ $payments->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
