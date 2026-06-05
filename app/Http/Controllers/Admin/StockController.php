<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockService::with(['service.category', 'user']);

        if ($request->filled('type') && in_array($request->type, ['in', 'out'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stocks      = $query->latest()->paginate(15)->withQueryString();
        $totalMasuk  = (int) StockService::where('type', 'in')->sum('quantity');
        $totalKeluar = (int) StockService::where('type', 'out')->sum('quantity');
        $services    = Service::with('category')->orderBy('name_service')->get();
        $categories  = Category::orderBy('name_category')->get();

        return view('admin.stock.index', compact(
            'stocks', 'totalMasuk', 'totalKeluar',
            'services', 'categories'
        ));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'in');

        if ($type === 'out') {
            $service = Service::find($request->service_id);
            if ($service && $request->quantity > $service->stock_service) {
                return redirect()->back()
                    ->withErrors(['quantity' => 'Jumlah melebihi stok tersedia (' . $service->stock_service . ' unit).'])
                    ->withInput();
            }
        }

        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:1000'],
            'type'       => ['required', 'in:in,out'],
        ], [
            'service_id.required' => 'Layanan wajib dipilih.',
            'quantity.required'   => 'Jumlah stock wajib diisi.',
            'quantity.min'        => 'Jumlah minimal 1.',
            'quantity.max'        => 'Jumlah maksimal 1000.',
        ]);

        DB::transaction(function () use ($request, $type) {
            $service     = Service::query()->lockForUpdate()->findOrFail($request->service_id);
            $stockBefore = $service->stock_service;
            $stockAfter  = $type === 'in'
                ? $stockBefore + $request->quantity
                : $stockBefore - $request->quantity;

            StockService::create([
                'service_id'   => $service->id,
                'user_id'      => auth()->id(),
                'quantity'     => $request->quantity,
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'type'         => $type,
            ]);

            $type === 'in'
                ? $service->increment('stock_service', $request->quantity)
                : $service->decrement('stock_service', $request->quantity);
        });

        $message = $type === 'in'
            ? 'Stock masuk berhasil ditambahkan.'
            : 'Order keluar berhasil ditambahkan.';

        return redirect()->route('stock.index')->with('success', $message);
    }
}