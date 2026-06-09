<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $years = range(now()->year, now()->year - 4);

        // Stat cards
        $totalStockMasuk  = (int) StockService::where('type', 'in')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalStockKeluar = (int) StockService::where('type', 'out')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalTransaksi   = (int) StockService::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)->count();

        // Grafik 12 bulan
        $chartMonths = [];
        $chartIn     = [];
        $chartOut    = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartMonths[] = \Carbon\Carbon::create($year, $m, 1)->translatedFormat('M');
            $chartIn[]     = (int) StockService::where('type', 'in')
                ->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('quantity');
            $chartOut[]    = (int) StockService::where('type', 'out')
                ->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('quantity');
        }

        // Top 5 tertinggi masuk bulan ini
        $topMasuk = StockService::with('service.category')
            ->where('type', 'in')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->select('service_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        // Top 5 tertinggi keluar bulan ini
        $topKeluar = StockService::with('service.category')
            ->where('type', 'out')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->select('service_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        // Stok kritis
        $kritisServices = StockService::with('service.category')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            // Ambil transaksi terbaru per service_id
            ->whereIn('id', function ($query) use ($year, $month) {
                $query->selectRaw('MAX(id)')
                    ->from('stock_services')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->groupBy('service_id');
            })
            // Dari transaksi terakhir itu, filter yang stock_after-nya <= 5
            ->where('stock_after', '<=', 5)
            ->orderBy('stock_after')
            ->get();

        // Laporan pesanan 
        $laporanPesanan = StockService::with('service.category')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->orderByDesc('created_at')->get();

        return view('admin.report.index', compact(
            'month', 'year', 'years',
            'totalStockMasuk', 'totalStockKeluar', 'totalTransaksi',
            'chartMonths', 'chartIn', 'chartOut',
            'topMasuk', 'topKeluar', 'kritisServices', 'laporanPesanan'
        ));
    }

    // ── Print PDF (HTML print view) ──────────────────────────────────────────────
    public function print(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $totalStockMasuk  = (int) StockService::where('type', 'in')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalStockKeluar = (int) StockService::where('type', 'out')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalTransaksi = (int) StockService::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)->count();

        $kritisServices = StockService::with('service.category')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            // Ambil transaksi terbaru per service_id
            ->whereIn('id', function ($query) use ($year, $month) {
                $query->selectRaw('MAX(id)')
                    ->from('stock_services')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->groupBy('service_id');
            })
            // Dari transaksi terakhir itu, filter yang stock_after-nya <= 5
            ->where('stock_after', '<=', 5)
            ->orderBy('stock_after')
            ->get();

        $laporanPesanan = StockService::with('service.category', 'user')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->orderByDesc('created_at')->get();

        return view('admin.report.print', compact(
            'month', 'year',
            'totalStockMasuk', 'totalStockKeluar', 'totalTransaksi',
            'kritisServices', 'laporanPesanan'
        ));
    }

    // ── Export Excel ─────────────────────────────────────────────────────────────
    public function export(Request $request): StreamedResponse
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $rows = StockService::with('service.category', 'user')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->orderByDesc('created_at')->get();

        $periode = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F_Y');
        $filename = "Laporan_Stock_{$periode}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel baca UTF-8 dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, ['#', 'Layanan', 'Kategori', 'Tipe', 'Kuantitas', 'Stok Awal', 'Stok Akhir', 'Oleh', 'Tanggal'], ';');

            foreach ($rows as $i => $s) {
                fputcsv($handle, [
                    $i + 1,
                    $s->service?->name_service ?? '-',
                    $s->service?->category?->name_category ?? '-',
                    $s->type === 'in' ? 'Masuk' : 'Keluar',
                    $s->quantity,
                    $s->stock_before ?? 0,
                    $s->stock_after ?? 0,
                    $s->user?->name ?? '-',
                    $s->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);
    }
}