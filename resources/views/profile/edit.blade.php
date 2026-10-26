@extends('layouts.app')

@section('title', 'Profil Akun')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Profil Akun</h2>
        <p class="text-secondary mb-0">
            Kelola informasi pribadi dan keamanan akun KosManager.
        </p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            Informasi profil berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-shield-check me-2"></i>
            Password berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form', [
                        'user' => $user
                    ])
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border border-danger-subtle shadow-sm rounded-3">
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</div>
@endsection