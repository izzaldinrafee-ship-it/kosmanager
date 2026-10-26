@extends('layouts.app')

@section('title', 'Edit Penghuni')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('tenants.index') }}"
            class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h2 class="fw-bold mt-3 mb-1">Edit Penghuni</h2>
        <p class="text-secondary mb-0">
            Perbarui informasi penghuni.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('tenants.update', $tenant) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $tenant->name) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">
                        Nomor HP
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $tenant->phone) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">
                        Alamat Asal
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        class="form-control"
                        rows="3"
                    >{{ old('address', $tenant->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="room_id" class="form-label">
                        Kamar
                    </label>
                    <select
                        id="room_id"
                        name="room_id"
                        class="form-select"
                        required
                    >
                        @foreach ($rooms as $room)
                            <option
                                value="{{ $room->id }}"
                                @selected(
                                    (string) old('room_id', $tenant->room_id)
                                    === (string) $room->id
                                )
                            >
                                Kamar {{ $room->room_number }}
                                - {{ $room->room_type }}
                                - Rp {{ number_format($room->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="move_in_date" class="form-label">
                        Tanggal Masuk
                    </label>
                    <input
                        type="date"
                        id="move_in_date"
                        name="move_in_date"
                        class="form-control"
                        value="{{ old(
                            'move_in_date',
                            $tenant->move_in_date
                                ? $tenant->move_in_date->format('Y-m-d')
                                : ''
                        ) }}"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">
                        Status Penghuni
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="form-select"
                        required
                    >
                        <option
                            value="Aktif"
                            @selected(old('status', $tenant->status) === 'Aktif')
                        >
                            Aktif
                        </option>

                        <option
                            value="Keluar"
                            @selected(old('status', $tenant->status) === 'Keluar')
                        >
                            Keluar
                        </option>
                    </select>

                    <small class="text-secondary">
                        Jika status diubah menjadi Keluar, kamar dapat tersedia
                        kembali apabila tidak ditempati penghuni aktif lain.
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('tenants.index') }}"
                        class="btn btn-light border">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection