
@extends('layouts.app')

@section('title', 'Data Kamar')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Data Kamar</h2>
            <p class="text-secondary mb-0">
                Kelola semua kamar kos Anda.
            </p>
        </div>

        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Kamar
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

    {{-- Tabel Kamar --}}
    <div class="content-card">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Daftar Kamar</h5>
                <p class="text-secondary small mb-0">
                    Informasi kamar dan harga sewa.
                </p>
            </div>

            <span class="badge text-bg-light border px-3 py-2">
                Total: {{ $rooms->count() }} kamar
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe</th>
                        <th>Harga Sewa</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="fw-semibold">
                                {{ $room->room_number }}
                            </td>

                            <td>{{ $room->room_type }}</td>

                            <td class="text-nowrap">
                                Rp{{ number_format($room->price, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($room->status === 'Kosong')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Kosong
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="bi bi-person-check me-1"></i>
                                        Terisi
                                    </span>
                                @endif
                            </td>

                            <td>{{ $room->description ?: '-' }}</td>

                            <td class="text-end text-nowrap">
                                <a
                                    href="{{ route('rooms.edit', $room) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit kamar"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form
                                    action="{{ route('rooms.destroy', $room) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus kamar ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus kamar"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-door-closed fs-1 text-secondary"></i>

                                <h6 class="fw-bold mt-3 mb-1">
                                    Belum ada data kamar
                                </h6>

                                <p class="text-secondary mb-3">
                                    Tambahkan kamar pertama untuk mulai mengelola kos.
                                </p>

                                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Tambah Kamar
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
