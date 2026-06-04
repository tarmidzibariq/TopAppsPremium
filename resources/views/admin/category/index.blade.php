@extends('layouts.master')

@section('title', 'Category')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Kategori</h4>
                <p class="text-body-secondary mb-0">Daftar semua kategori yang tersedia</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-primary">Total: {{ $categories->total() }} kategori</span>
                <a href="{{ route('category.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bx bx-filter-alt me-2"></i>Filter</h6>
                @if(request()->hasAny(['category_id', 'search']))
                    <a href="{{ route('category.index') }}" class="btn btn-sm btn-label-secondary">
                        <i class="bx bx-x me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('category.index') }}">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Cari Kategori</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nama kategori..." value="{{ request('search') }}">
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

        {{-- Table --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Daftar Kategori</h5>
                <span class="badge bg-label-secondary">{{ $categories->total() }} kategori</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Tanggal dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name_category }}</td>
                                <td>{{ $category->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('category.edit', $category) }}"
                                            class="btn btn-sm btn-icon btn-label-primary"
                                            title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-icon btn-label-danger"
                                            title="Hapus"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name_category }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-5">
                                    Belum ada kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($categories->hasPages())
                <div class="card-footer">
                    {{ $categories->links() }}
                </div>
            @endif
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
                <h5 class="modal-title">Hapus Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-trash bx-lg text-danger mb-3 d-block"></i>
                <p class="mb-1">Yakin ingin menghapus kategori:</p>
                <strong id="deleteCategoryName"></strong>
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
    document.getElementById('modalDelete').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('deleteCategoryName').textContent = btn.dataset.name;
        document.getElementById('formDelete').action = `/admin/category/${btn.dataset.id}`;
    });
</script>
@endpush