<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stock — {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 24px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 12px; }
        .header h2 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #666; }

        .stats { display: flex; gap: 16px; margin-bottom: 20px; }
        .stat-box { flex: 1; border: 1px solid #ddd; border-radius: 6px; padding: 10px 14px; text-align: center; }
        .stat-box .label { font-size: 11px; color: #888; margin-bottom: 4px; }
        .stat-box .value { font-size: 20px; font-weight: bold; }
        .stat-box.masuk .value  { color: #28a745; }
        .stat-box.keluar .value { color: #dc3545; }
        .stat-box.total .value  { color: #0d6efd; }
        .stat-box.kritis .value { color: #fd7e14; }

        h3 { font-size: 13px; margin-bottom: 8px; border-left: 3px solid #0d6efd; padding-left: 8px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        th { background: #f0f0f0; padding: 7px 8px; text-align: left; border: 1px solid #ccc; font-weight: 600; }
        td { padding: 6px 8px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) td { background: #fafafa; }

        .badge-masuk  { color: #28a745; font-weight: bold; }
        .badge-keluar { color: #dc3545; font-weight: bold; }
        .badge-kritis { color: #fd7e14; font-weight: bold; }
        .badge-habis  { color: #dc3545; font-weight: bold; }

        .no-print { margin-bottom: 16px; }
        .btn-print {
            background: #0d6efd; color: #fff; border: none;
            padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 13px; margin-right: 8px;
        }
        .btn-back {
            background: #6c757d; color: #fff; border: none;
            padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 13px; text-decoration: none;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    {{-- Tombol aksi (hilang saat print) --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <a href="{{ url()->previous() }}" class="btn-back">← Kembali</a>
    </div>

    {{-- Header laporan --}}
    <div class="header">
        <h2>Laporan Mutasi Stock</h2>
        <p>Top Apps Premium &nbsp;|&nbsp; Periode: {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</p>
        <p>Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    {{-- Stat cards --}}
    <div class="stats">
        <div class="stat-box masuk">
            <div class="label">Stock Masuk</div>
            <div class="value">{{ number_format($totalStockMasuk) }}</div>
            <div class="label">unit</div>
        </div>
        <div class="stat-box keluar">
            <div class="label">Stock Keluar</div>
            <div class="value">{{ number_format($totalStockKeluar) }}</div>
            <div class="label">unit</div>
        </div>
        <div class="stat-box total">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ number_format($totalTransaksi) }}</div>
            <div class="label">transaksi</div>
        </div>
        <div class="stat-box kritis">
            <div class="label">Stok Kritis</div>
            <div class="value">{{ $kritisServices->count() }}</div>
            <div class="label">layanan ≤ 5 unit</div>
        </div>
    </div>

    {{-- Tabel stok kritis --}}
    @if ($kritisServices->count())
    <h3>Layanan Stok Kritis</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Layanan</th>
                <th>Kategori</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kritisServices as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->service?->name_service }}</td>
                <td>{{ $k->service?->category?->name_category ?? '-' }}</td>
                <td>
                    @if ($k->stock_service == 0)
                        <span class="badge-habis">Habis</span>
                    @else
                        <span class="badge-kritis">{{ $k->stock_service }} unit</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Tabel log mutasi --}}
    <h3>Log Mutasi Stock</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Layanan</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Kuantitas</th>
                <th>Stok Awal</th>
                <th>Stok Akhir</th>
                <th>Oleh</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporanPesanan as $i => $s)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $s->service?->name_service ?? '-' }}</td>
                <td>{{ $s->service?->category?->name_category ?? '-' }}</td>
                <td>
                    @if ($s->type === 'in')
                        <span class="badge-masuk">▲ Masuk</span>
                    @else
                        <span class="badge-keluar">▼ Keluar</span>
                    @endif
                </td>
                <td>{{ number_format($s->quantity) }}</td>
                <td>{{ number_format($s->stock_before ?? 0) }}</td>
                <td>{{ number_format($s->stock_after ?? 0) }}</td>
                <td>{{ $s->user?->name ?? '-' }}</td>
                <td>{{ $s->created_at->translatedFormat('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding: 20px; color:#888;">Belum ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>