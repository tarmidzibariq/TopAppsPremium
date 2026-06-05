@extends('layouts.master')

@section('title', 'Edit Password User')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Ubah Password</h4>
                <p class="text-body-secondary mb-0">Perbarui password akun untuk menjaga keamanan akses sistem.</p>
                </p>
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
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">

            {{-- Riwayat Stock --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Form Ubah Password</h5>
                        <p class="text-body-secondary">Gunakan password baru yang kuan untuk menjaga keamanan akun.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                {{-- Password Lama --}}
                                <div class="col-sm-12">
                                    <label class="text-body-secondary small d-block mb-1">Password Lama <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('password_old') is-invalid @enderror"
                                            id="password_old" name="password_old" placeholder="Masukkan password lama"
                                            required>
                                        <span class="input-group-text cursor-pointer toggle-password"
                                            data-target="#password_old">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                        @error('password_old')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password Baru --}}
                                <div class="col-sm-12">
                                    <label class="text-body-secondary small d-block mb-1">Password Baru <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('password_new') is-invalid @enderror"
                                            id="password_new" name="password_new" placeholder="Masukkan password baru"
                                            required>
                                        <span class="input-group-text cursor-pointer toggle-password"
                                            data-target="#password_new">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                        @error('password_new')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block mt-1">Minimal 8 karakter</small>
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div class="col-sm-12">
                                    <label class="text-body-secondary small d-block mb-1">Konfirmasi Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('password_new_confirmation') is-invalid @enderror"
                                            id="password_new_confirmation" name="password_new_confirmation"
                                            placeholder="Konfirmasi password baru" required>
                                        <span class="input-group-text cursor-pointer toggle-password"
                                            data-target="#password_new_confirmation">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                        @error('password_new_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ url()->previous() }}" class="btn btn-label-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i>Perbarui Password
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
            <div
                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>
                        document.write(new Date().getFullYear());

                    </script> Top Apps Premium
                </div>
            </div>
        </div>
    </footer>
    <div class="content-backdrop fade"></div>
</div>

@endsection
@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePasswordElements = document.querySelectorAll('.toggle-password');

    togglePasswordElements.forEach(function (element) {
        element.addEventListener('click', function () {
            // Ambil target id input dari atribut data-target
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.querySelector(targetId);
            const icon = this.querySelector('i');

            if (passwordInput && icon) {
                // Tukar tipe input antara password dan text
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    // Ubah ikon jadi mata terbuka
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    passwordInput.type = 'password';
                    // Ubah ikon jadi mata tertutup
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            }
        });
    });
});
</script>
@endpush