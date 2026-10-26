
@extends('layouts.app')

@section('title', 'Tambah Penghuni')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('tenants.index') }}"
           class="text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Data Penghuni
        </a>

        <h2 class="fw-bold mb-1">Tambah Penghuni</h2>
        <p class="text-secondary mb-0">
            Masukkan informasi penghuni baru.
        </p>
    </div>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Periksa kembali data berikut:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Form --}}
    <div class="content-card" style="max-width: 850px;">

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-person-plus"></i>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Informasi Penghuni</h5>
                <p class="text-secondary small mb-0">
                    Lengkapi informasi penghuni dan kamar yang akan ditempati.
                </p>
            </div>
        </div>

        @if ($rooms->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Belum ada kamar kosong.</strong>
                Tambahkan kamar baru atau pastikan ada kamar yang tersedia.
            </div>

            <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Kamar
            </a>

            <a href="{{ route('tenants.index') }}"
               class="btn btn-light border ms-1">
                Kembali
            </a>
        @else
            <form action="{{ route('tenants.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        maxlength="255"
                        required
                        autofocus
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">
                        Nomor HP <span class="text-danger">*</span>
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890"
                        maxlength="30"
                        required
                    >

                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">
                        Alamat Asal
                        <span class="text-secondary fw-normal">(Opsional)</span>
                    </label>

                    <textarea
                        name="address"
                        id="address"
                        class="form-control @error('address') is-invalid @enderror"
                        rows="3"
                        placeholder="Masukkan alamat asal penghuni"
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Pilih Kamar --}}
                <div class="mb-3">
                    <label for="room_id" class="form-label fw-semibold">
                        Pilih Kamar <span class="text-danger">*</span>
                    </label>

                    <select
                        name="room_id"
                        id="room_id"
                        class="form-select @error('room_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih kamar kosong --</option>

                        @foreach ($rooms as $room)
                            <option
                                value="{{ $room->id }}"
                                @selected((string) old('room_id') === (string) $room->id)
                            >
                                Kamar {{ $room->room_number }}
                                — {{ $room->room_type }}
                                — Rp{{ number_format($room->price, 0, ',', '.') }}/bulan
                            </option>
                        @endforeach
                    </select>

                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        Pilih kamar yang tersedia untuk ditempati penghuni.
                    </div>
                </div>

                {{-- Tanggal Masuk --}}
                <div class="mb-4">
                    <label for="move_in_date" class="form-label fw-semibold">
                        Tanggal Masuk <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="move_in_date"
                        id="move_in_date"
                        class="form-control @error('move_in_date') is-invalid @enderror"
                        value="{{ old('move_in_date', date('Y-m-d')) }}"
                        required
                    >

                    @error('move_in_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                {{-- Tombol --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('tenants.index') }}"
                       class="btn btn-light border">
                        <i class="bi bi-x-lg me-1"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Simpan Penghuni
                    </button>
                </div>
            </form>
        @endif

    </div>
</div>
@endsection
