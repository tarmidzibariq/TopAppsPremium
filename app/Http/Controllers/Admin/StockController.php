<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index()
    {
        $stocks = StockService::query()
            ->with(['service.category', 'user'])
            ->where('type', 'in')
            ->latest()
            ->paginate(15);

        $services = Service::with('category')
            ->orderBy('name_service')
            ->get();

        $totalMasuk = (int) StockService::where('type', 'in')->sum('quantity');

        return view('admin.stock.index', compact('stocks', 'services', 'totalMasuk'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:255'],
        ], [
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan tidak valid.',
            'quantity.required' => 'Jumlah stock wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 255.',
        ]);

        DB::transaction(function () use ($validated) {
            $service = Service::query()->lockForUpdate()->findOrFail($validated['service_id']);

            StockService::create([
                'service_id' => $service->id,
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'type' => 'in',
            ]);

            $service->increment('stock_service', $validated['quantity']);
        });

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock masuk berhasil ditambahkan.');
    }
}
