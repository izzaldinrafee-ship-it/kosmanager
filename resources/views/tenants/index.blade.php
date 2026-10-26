
@extends('layouts.app')

@section('title', 'Data Penghuni | KosManager')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Tutup"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Tutup"></button>
    </div>
@endif

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Data Penghuni</h2>
            <p class="text-secondary mb-0">
                Kelola penghuni dan kamar kos.
            </p>
        </div>

        <a href="{{ route('tenants.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Tambah Penghuni
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Pencarian dan Filter --}}
    <div class="content-card mb-4">
        <form action="{{ route('tenants.index') }}" method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-12 col-lg-6">
                    <label for="search" class="form-label fw-semibold">
                        Cari Penghuni
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Nama, nomor HP, atau nomor kamar..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-5 col-lg-3">
                    <label for="status" class="form-label fw-semibold">
                        Status Penghuni
                    </label>

                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Aktif"
                            @selected(request('status') === 'Aktif')>
                            Aktif
                        </option>
                        <option value="Keluar"
                            @selected(request('status') === 'Keluar')>
                            Keluar
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-7 col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('tenants.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- Tabel Penghuni --}}
    <div class="content-card">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Penghuni</h5>
                <p class="text-secondary small mb-0">
                    Data penghuni yang terdaftar di KosManager.
                </p>
            </div>

            <span class="badge text-bg-light border">
                Total: {{ $tenants->total() }} penghuni
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>No.</th>
                        <th>Nama Penghuni</th>
                        <th>No. HP</th>
                        <th>Kamar</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tenants as $tenant)
                        <tr>
                            <td>{{ $tenants->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $tenant->name }}
                                </div>
                                @if ($tenant->address)
                                    <small class="text-secondary">
                                        {{ $tenant->address }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                <a href="tel:{{ $tenant->phone }}"
                                   class="text-decoration-none text-body">
                                    {{ $tenant->phone }}
                                </a>
                            </td>

                            <td>
                                <span class="badge text-bg-light border">
                                    <i class="bi bi-door-open me-1"></i>
                                    {{ $tenant->room->room_number ?? 'Tidak tersedia' }}
                                </span>
                            </td>

                            <td>
                                {{ $tenant->move_in_date
                                    ? $tenant->move_in_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                @if ($tenant->status === 'Aktif')
                                    <span class="badge text-bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary">
                                        Keluar
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a
                                        href="{{ route('tenants.edit', $tenant) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Edit penghuni"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('tenants.destroy', $tenant) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data penghuni ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus penghuni"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-people fs-1 text-secondary"></i>

                                <h6 class="fw-bold mt-3 mb-2">
                                    @if (request()->filled('search') || request()->filled('status'))
                                        Data penghuni tidak ditemukan
                                    @else
                                        Belum ada data penghuni
                                    @endif
                                </h6>

                                <p class="text-secondary mb-3">
                                    @if (request()->filled('search') || request()->filled('status'))
                                        Coba ubah kata kunci atau reset filter pencarian.
                                    @else
                                        Tambahkan penghuni pertama untuk mulai mengelola kos.
                                    @endif
                                </p>

                                @if (request()->filled('search') || request()->filled('status'))
                                    <a href="{{ route('tenants.index') }}"
                                       class="btn btn-outline-secondary">
                                        Reset Pencarian
                                    </a>
                                @else
                                    <a href="{{ route('tenants.create') }}"
                                       class="btn btn-primary">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Tambah Penghuni
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tenants->hasPages())
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                <small class="text-secondary">
                    Menampilkan {{ $tenants->firstItem() }}–{{ $tenants->lastItem() }}
                    dari {{ $tenants->total() }} data
                </small>

                {{ $tenants->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
