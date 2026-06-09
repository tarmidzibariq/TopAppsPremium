@extends('layouts.master')

@section('title', 'Mutasi Stock')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Mutasi Stock</h4>
                <p class="text-body-secondary mb-0">Log seluruh transaksi masuk dan keluar stock layanan</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-success">
                    <i class="bx bx-trending-up me-1"></i>Masuk: {{ number_format($totalMasuk) }} unit
                </span>
                <span class="badge bg-label-danger">
                    <i class="bx bx-trending-down me-1"></i>Keluar: {{ number_format($totalKeluar) }} unit
                </span>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateStock">
                    <i class="bx bx-plus me-1"></i> Tambah Transaksi
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bx bx-filter-alt me-2"></i>Filter</h6>
                @if(request()->hasAny(['type', 'category_id', 'service_id', 'date_from', 'date_to']))
                    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-label-secondary">
                        <i class="bx bx-x me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('stock.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select">
                                <option value="">Semua</option>
                                <option value="in"  {{ request('type') === 'in'  ? 'selected' : '' }}>Masuk</option>
                                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Log Mutasi Stock</h5>
                <span class="badge bg-label-secondary">{{ $stocks->total() }} transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan</th>
                            <th>Kategori</th>
                            <th>Mutasi</th>
                            <th>Stok Awal</th>
                            <th>Stok Akhir</th>
                            <th>Oleh</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->id }}</td>
                                <td>
                                    <span class="fw-medium">{{ $stock->service?->name_service ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $stock->service?->category?->name_category ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($stock->type === 'in')
                                        <span class="text-success fw-semibold">
                                            +{{ number_format($stock->quantity) }}
                                        </span>
                                        <span class="badge bg-label-success ms-1">Masuk</span>
                                    @else
                                        <span class="text-danger fw-semibold">
                                            -{{ number_format($stock->quantity) }}
                                        </span>
                                        <span class="badge bg-label-danger ms-1">Keluar</span>
                                    @endif
                                </td>
                                <td class="text-body-secondary">
                                    {{ number_format($stock->stock_before ?? 0) }} unit
                                </td>
                                <td>
                                    <span class="fw-medium">{{ number_format($stock->stock_after ?? 0) }} unit</span>
                                </td>
                                <td>{{ $stock->user?->name ?? '-' }}</td>
                                <td>
                                    <small>{{ $stock->created_at->translatedFormat('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-body-secondary py-5">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $stocks->links('pagination::bootstrap-5') }}

            {{-- @if ($stocks->hasPages())
                <div class="card-footer">
                    {{ $stocks->links() }}
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

{{-- Modal --}}
<div class="modal fade" id="modalCreateStock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('stock.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeIn"
                                    value="in" {{ old('type', 'in') === 'in' ? 'checked' : '' }}>
                                <label class="form-check-label text-success fw-medium" for="typeIn">
                                    <i class="bx bx-trending-up me-1"></i>Stock Masuk
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeOut"
                                    value="out" {{ old('type') === 'out' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-medium" for="typeOut">
                                    <i class="bx bx-trending-down me-1"></i>Order Keluar
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="service_id" class="form-label">Layanan <span class="text-danger">*</span></label>
                        <select id="service_id" name="service_id"
                            class="form-select @error('service_id') is-invalid @enderror" required>
                            <option value="" disabled {{ old('service_id') ? '' : 'selected' }}>Pilih layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    data-stock="{{ $service->stock_service }}"
                                    {{ (string) old('service_id') === (string) $service->id ? 'selected' : '' }}>
                                    {{ $service->name_service }}
                                    ({{ $service->category?->name_category ?? '-' }} — stock: {{ $service->stock_service }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small id="stockInfo" class="mt-1 d-block"></small>
                    </div>

                    <div class="mb-0">
                        <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="1" max="1000"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', 1) }}" required />
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- Preview perhitungan --}}
                        <div id="calcPreview" class="mt-2 px-3 py-2 rounded bg-label-secondary small" style="display:none;">
                            <span id="calcText"></span>
                        </div>
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
    // Filter kategori → layanan
    const filterCategory = document.getElementById('filterCategory');
    const filterService  = document.getElementById('filterService');
    const allOptions     = Array.from(filterService.options);

    filterCategory.addEventListener('change', function () {
        const selected = this.value;
        filterService.innerHTML = '<option value="">Semua Layanan</option>';
        allOptions.forEach(opt => {
            if (opt.value === '') return;
            if (!selected || opt.dataset.category === selected) {
                filterService.appendChild(opt.cloneNode(true));
            }
        });
    });

    // Preview perhitungan stok awal → mutasi → stok akhir
    const modalService = document.getElementById('service_id');
    const qtyInput     = document.getElementById('quantity');
    const stockInfo    = document.getElementById('stockInfo');
    const calcPreview  = document.getElementById('calcPreview');
    const calcText     = document.getElementById('calcText');

    function updatePreview() {
        const selected = modalService.options[modalService.selectedIndex];
        const stock    = parseInt(selected?.dataset?.stock ?? '');
        const qty      = parseInt(qtyInput.value) || 0;
        const typeIn   = document.getElementById('typeIn').checked;

        if (!isNaN(stock) && qty > 0) {
            const after  = typeIn ? stock + qty : stock - qty;
            const symbol = typeIn ? '+' : '-';
            const color  = typeIn ? '#71dd37' : '#ff3e1d';

            stockInfo.innerHTML = `Stok tersedia: <strong>${stock} unit</strong>`;
            stockInfo.className = stock > 0 ? 'text-success small mt-1 d-block' : 'text-danger small mt-1 d-block';

            calcText.innerHTML =
                `Stok Awal: <strong>${stock}</strong> ` +
                `<span style="color:${color}; font-weight:600;">${symbol}${qty}</span> ` +
                `→ Stok Akhir: <strong>${after < 0 ? '<span class="text-danger">' + after + ' (tidak cukup)</span>' : after}</strong>`;

            calcPreview.style.display = 'block';
        } else {
            calcPreview.style.display = 'none';
            stockInfo.textContent = '';
        }
    }

    modalService.addEventListener('change', updatePreview);
    qtyInput.addEventListener('input', updatePreview);
    document.querySelectorAll('input[name="type"]').forEach(r => r.addEventListener('change', updatePreview));

    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalCreateStock');
        if (modal) bootstrap.Modal.getOrCreateInstance(modal).show();
    });
    @endif
</script>
@endpush