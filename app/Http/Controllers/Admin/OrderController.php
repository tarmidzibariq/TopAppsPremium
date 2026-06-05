<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
     public function index(Request $request)
    {
        $query = StockService::with(['service.category', 'user'])
            ->where('type', 'out');

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter layanan
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filter range tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $totalKeluar = $query->sum('quantity'); // ikut filter

        $services = Service::with('category')->get();
        $categories = Category::all(); 

        return view('admin.order.index', compact('orders', 'totalKeluar', 'services', 'categories'));
    }   

    public function store(Request $request)
    {
        // jika quantity melebihi stok layanan yang tersedia, tampilkan error
        $service = Service::find($request->service_id);
        if($request->quantity > $service->stock_service) {
            return redirect()->back()
                ->withErrors(['quantity' => 'Jumlah melebihi stok layanan yang tersedia.'])
                ->withInput();  
        }

        // validasi input
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ], [
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan tidak valid.',
            'quantity.required' => 'Jumlah stock wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 1000.',
        ]);

        DB::transaction(function () use ($validated) {
            $service = Service::query()->lockForUpdate()->findOrFail($validated['service_id']);

            StockService::create([
                'service_id' => $service->id,
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'type' => 'out',
            ]);

            $service->decrement('stock_service', $validated['quantity']);
        });

        return redirect()
            ->route('order.index')
            ->with('success', 'Order keluar berhasil ditambahkan.');
    }
}
