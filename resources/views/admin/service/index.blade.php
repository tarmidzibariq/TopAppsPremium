@extends('layouts.master')

@section('title', 'Layanan')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Layanan</h4>
                <p class="text-body-secondary mb-0">Daftar semua layanan yang tersedia</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-primary">Total: {{ $services->total() }} layanan</span>
                <a href="{{ route('service.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Layanan
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
                    <a href="{{ route('service.index') }}" class="btn btn-sm btn-label-secondary">
                        <i class="bx bx-x me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('service.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Cari Layanan</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nama layanan..." value="{{ request('search') }}">
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
                <h5 class="card-title mb-0">Daftar Layanan</h5>
                <span class="badge bg-label-secondary">{{ $services->total() }} layanan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama Layanan</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>
                                    @if ($service->image_service)
                                        <img src="{{ asset('storage/' . $service->image_service) }}"
                                            alt="{{ $service->name_service }}"
                                            class="rounded"
                                            style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="bx bx-image-alt"></i>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $service->name_service }}</span>
                                    @if ($service->description_service)
                                        <br><small class="text-body-secondary text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $service->description_service }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $service->category?->name_category ?? '-' }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($service->price_service, 0, ',', '.') }}</td>
                                <td>
                                    @if ($service->stock_service > 10)
                                        <span class="badge bg-label-success">{{ $service->stock_service }} unit</span>
                                    @elseif ($service->stock_service > 0)
                                        <span class="badge bg-label-warning">{{ $service->stock_service }} unit</span>
                                    @else
                                        <span class="badge bg-label-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('service.show', $service) }}"
                                            class="btn btn-sm btn-icon btn-label-info" title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('service.edit', $service) }}"
                                            class="btn btn-sm btn-icon btn-label-primary"
                                            title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-icon btn-label-danger"
                                            title="Hapus"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete"
                                            data-id="{{ $service->id }}"
                                            data-action="{{ route('service.destroy', $service->id) }}"
                                            data-name="{{ $service->name_service }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-5">
                                    Belum ada layanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($services->hasPages())
                <div class="card-footer">
                    {{ $services->links() }}
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
                <h5 class="modal-title">Hapus Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-trash bx-lg text-danger mb-3 d-block"></i>
                <p class="mb-1">Yakin ingin menghapus layanan:</p>
                <strong id="deleteServiceName"></strong>
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
                const serviceName = button.getAttribute('data-name');
                
                // Ambil elemen Form dan Text di dalam modal
                const formDelete = document.getElementById('formDelete');
                const deleteServiceName = document.getElementById('deleteServiceName');
                
                // Masukkan datanya ke dalam modal
                formDelete.action = actionUrl;
                deleteServiceName.textContent = serviceName;
            });
        }
    });
</script>
@endpush