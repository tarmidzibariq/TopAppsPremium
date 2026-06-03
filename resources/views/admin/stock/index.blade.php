@extends('layouts.master')

@section('title', 'Stock Masuk')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Stock Masuk</h4>
                <p class="text-body-secondary mb-0">Daftar transaksi penambahan stock (type: in)</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-success">Total masuk: {{ number_format($totalMasuk) }} unit</span>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateStock">
                    <i class="bx bx-plus me-1"></i> Tambah Stock
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bx bx-filter-alt me-2"></i>Filter</h6>
                @if(request()->hasAny(['category_id', 'service_id', 'date_from', 'date_to']))
                    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-label-secondary">
                        <i class="bx bx-x me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('stock.index') }}">
                    <div class="row g-3">
                        {{-- Filter Kategori --}}
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" id="filterCategory">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Layanan --}}
                        <div class="col-md-3">
                            <label class="form-label">Layanan</label>
                            <select name="service_id" class="form-select" id="filterService">
                                <option value="">Semua Layanan</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        data-category="{{ $service->category_id }}"
                                        {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name_service }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date From --}}
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control"
                                value="{{ request('date_from') }}">
                        </div>

                        {{-- Date To --}}
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control"
                                value="{{ request('date_to') }}">
                        </div>

                        {{-- Submit --}}
                        <div class="col-md-2 d-flex align-items-end">
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
                <h5 class="card-title mb-0">Riwayat Stock Masuk</h5>
                <span class="badge bg-label-secondary">{{ $stocks->total() }} transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Stock Saat Ini</th>
                            <th>Oleh</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->id}}</td>
                                <td>
                                    <span class="fw-medium">{{ $stock->service?->name_service ?? '-' }}</span>
                                </td>
                                <td>{{ $stock->service?->category?->name_category ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-success">+{{ $stock->quantity }}</span>
                                </td>
                                <td>{{ $stock->service?->stock_service ?? '-' }} unit</td>
                                <td>{{ $stock->user?->name ?? '-' }}</td>
                                <td>
                                    <small>{{ $stock->created_at->translatedFormat('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-5">
                                    Belum ada transaksi stock masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($stocks->hasPages())
                <div class="card-footer">
                    {{ $stocks->links() }}
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

{{-- Modal (sama seperti sebelumnya) --}}
<div class="modal fade" id="modalCreateStock" tabindex="-1" aria-labelledby="modalCreateStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('stock.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateStockLabel">Tambah Stock Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="service_id" class="form-label">Layanan</label>
                        <select id="service_id" name="service_id"
                            class="form-select @error('service_id') is-invalid @enderror" required>
                            <option value="" disabled {{ old('service_id') ? '' : 'selected' }}>Pilih layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ (string) old('service_id') === (string) $service->id ? 'selected' : '' }}>
                                    {{ $service->name_service }}
                                    ({{ $service->category?->name_category ?? 'Tanpa kategori' }} — stock: {{ $service->stock_service }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="quantity" class="form-label">Jumlah Stock Masuk</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="1000"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', 1) }}" required />
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">Maksimal 1000 unit per transaksi.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" {{ $services->isEmpty() ? 'disabled' : '' }}>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
    // Dynamic filter: pilih kategori → filter dropdown layanan
    const filterCategory = document.getElementById('filterCategory');
    const filterService = document.getElementById('filterService');
    const allOptions = Array.from(filterService.options);

    filterCategory.addEventListener('change', function () {
        const selectedCategory = this.value;
        const currentService = "{{ request('service_id') }}";

        // Reset service options
        filterService.innerHTML = '<option value="">Semua Layanan</option>';

        allOptions.forEach(opt => {
            if (opt.value === '') return;
            if (!selectedCategory || opt.dataset.category === selectedCategory) {
                filterService.appendChild(opt.cloneNode(true));
            }
        });
    });

    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modalCreateStock');
        if (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).show();
        }
    });
    @endif
</script>
@endpush