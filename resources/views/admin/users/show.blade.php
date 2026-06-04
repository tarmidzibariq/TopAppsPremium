@extends('layouts.master')

@section('title', 'Detail Pengguna')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Detail Pengguna</h4>
                <p class="text-body-secondary mb-0">Informasi akun <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                @if ($user->id !== auth()->id())
                    <button type="button" class="btn btn-danger"
                        data-bs-toggle="modal" data-bs-target="#modalDelete">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-label-primary mb-4"
                            style="width: 90px; height: 90px; font-size: 2rem; font-weight: 700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-body-secondary mb-3">{{ $user->email }}</p>

                        @if ($user->email_verified_at)
                            <span class="badge bg-label-success">
                                <i class="bx bx-check me-1"></i>Email Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-label-warning">
                                <i class="bx bx-time me-1"></i>Belum Terverifikasi
                            </span>
                        @endif

                        @if ($user->id === auth()->id())
                            <div class="mt-3">
                                <span class="badge bg-label-info">Akun Anda</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">ID Pengguna</span>
                            <span class="fw-medium">#{{ $user->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Bergabung</span>
                            <span class="small">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <span class="text-body-secondary small">Diperbarui</span>
                            <span class="small">{{ $user->updated_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Detail Informasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label class="text-body-secondary small d-block mb-1">Nama Lengkap</label>
                                <span class="fw-medium">{{ $user->name }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-body-secondary small d-block mb-1">Alamat Email</label>
                                <span class="fw-medium">{{ $user->email }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-body-secondary small d-block mb-1">Status Verifikasi</label>
                                @if ($user->email_verified_at)
                                    <span class="badge bg-label-success">Terverifikasi</span>
                                    <div class="text-body-secondary small mt-1">
                                        {{ $user->email_verified_at->translatedFormat('d M Y H:i') }}
                                    </div>
                                @else
                                    <span class="badge bg-label-warning">Belum Terverifikasi</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <label class="text-body-secondary small d-block mb-1">Tanggal Daftar</label>
                                <span class="fw-medium">{{ $user->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                        </div>
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

{{-- Modal Hapus --}}
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-user-x bx-lg text-danger mb-3 d-block"></i>
                <p class="mb-1">Yakin ingin menghapus pengguna:</p>
                <strong>{{ $user->name }}</strong>
                <p class="text-body-secondary small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('users.destroy', $user) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection