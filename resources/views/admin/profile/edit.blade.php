@extends('layouts.master')

@section('title', 'Edit Profil')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Profil Pengguna</h4>
                <p class="text-body-secondary mb-0">Informasi lengkap profil <strong>{{ Auth::user()->name }}.</strong></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- Info Dasar --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center pb-4">
                        <img src="{{ asset('assets-adminTemplate/img/avatars/1.png') }}"
                            alt="profile"
                            class="rounded mb-4 mt-2"
                            style="width: 140px; height: 140px; object-fit: cover;">
                       

                        <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                        <span class="badge bg-label-info mb-3">
                            Administrator
                        </span>

                        {{-- <p class="text-body-primary small mb-0">AKTIF</p> --}}
                        @if (Auth::user()->email_verified_at)
                            <span class="badge bg-label-success">
                                <i class="bx bx-check me-1"></i>Email Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-label-warning">
                                <i class="bx bx-time me-1"></i>Belum Terverifikasi
                            </span>
                        @endif
                       
                    </div>
                    <div class="card-body border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Email</span>
                            <span class="fw-semibold text-primary">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Dibuat</span>
                            <span class="small">{{ Auth::user()->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <span class="text-body-secondary small">Diperbarui</span>
                            <span class="small">{{ Auth::user()->updated_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Stock --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Informasi</h5>
                        <p class="text-body-secondary">Perbarui informasi pengguna yang sedang login</p>
                        
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                        <div class="row g-2">
                                @method('PUT')
                                <div class="col-sm-6">
                                    <label class="text-body-secondary small d-block mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ Auth::user()->name }}" name="name" maxlength="40" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-body-secondary">Maksimal 40 karakter.</small>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-body-secondary small d-block mb-1">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ Auth::user()->email }}" name="email" maxlength="40" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ url()->previous() }}" class="btn btn-label-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i>Perbarui Profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>document.write(new Date().getFullYear());</script> Top Apps Premium
                </div>
            </div>
        </div>
    </footer>
    <div class="content-backdrop fade"></div>
</div>

@endsection