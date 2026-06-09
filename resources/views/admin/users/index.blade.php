@extends('layouts.master')

@section('title', 'Pengguna')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Pengguna</h4>
                <p class="text-body-secondary mb-0">Kelola akun pengguna sistem</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-primary">Total: {{ $users->total() }} pengguna</span>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Pengguna
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

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bx bx-filter-alt me-2"></i>Filter</h6>
                @if (request()->filled('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-label-secondary">
                        <i class="bx bx-x me-1"></i>Reset
                    </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label">Cari Pengguna</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nama atau email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-search me-1"></i>Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Daftar Pengguna</h5>
                <span class="badge bg-label-secondary">{{ $users->total() }} pengguna</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            {{-- <th>Verifikasi Email</th> --}}
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id}}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                <span class="badge bg-label-warning ms-1" style="font-size: 10px;">Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                {{-- <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-label-success">
                                            <i class="bx bx-check me-1"></i>Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-label-warning">
                                            <i class="bx bx-time me-1"></i>Belum
                                        </span>
                                    @endif
                                </td> --}}
                                <td>
                                    <small>{{ $user->created_at->translatedFormat('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="btn btn-sm btn-icon btn-label-info" title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="btn btn-sm btn-icon btn-label-primary" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-label-danger"
                                                title="Hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete"
                                                data-id="{{ $user->id }}"
                                                data-action="{{ route('users.destroy', $user->id) }}"
                                                data-name="{{ $user->name }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-body-secondary py-5">
                                    Belum ada pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $users->links('pagination::bootstrap-5') }}
            {{-- @if ($users->hasPages())
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            @endif --}}
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
                <strong id="deleteUserName"></strong>
                <p class="text-body-secondary small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formDelete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalDelete = document.getElementById('modalDelete');
        
        if (modalDelete) {
            modalDelete.addEventListener('show.bs.modal', function (event) {
                // Tombol yang memicu modal muncul
                const button = event.relatedTarget;
                
                // Ambil data action (URL) dan nama dari tombol
                const actionUrl = button.getAttribute('data-action');
                const userName = button.getAttribute('data-name');
                
                // Ambil elemen Form dan Text di dalam modal
                const formDelete = document.getElementById('formDelete');
                const deleteUserName = document.getElementById('deleteUserName');
                
                // Masukkan datanya ke dalam modal
                formDelete.action = actionUrl;
                deleteUserName.textContent = userName;
            });
        }
    });
</script>
@endpush