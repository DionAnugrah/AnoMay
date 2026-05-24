<?php

namespace App\Http\Controllers;

use App\Models\StockAllocation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class StockAllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = StockAllocation::with(['user:id,name', 'product:id,name,price']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $allocations = $query->orderBy('date', 'desc')->get();
        $sellers     = User::where('role', 'penjual')->get();
        $products    = Product::all();

        return view('admin.stock.index', compact('allocations', 'sellers', 'products'));
    }

    public function store(Request $request)
    {
        if ($request->has('allocations')) {
            return $this->storeBulk($request);
        }

        $validated = $request->validate([
            'user_id'    => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'date'       => ['required', 'date'],
            'qty_given'  => ['required', 'integer', 'min:1'],
        ]);

        $exists = StockAllocation::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Alokasi stok untuk penjual dan item ini pada tanggal tersebut sudah ada.');
        }

        $user = User::find($validated['user_id']);
        // Pastikan role-nya adalah penjual (menyesuaikan metode isPenjual() yang mungkin tidak ada)
        if ($user->role !== 'penjual') {
            return back()->with('error', 'User yang dipilih bukan penjual.');
        }

        StockAllocation::create($validated);

        return back()->with('success', 'Alokasi stok berhasil disimpan ke panci.');
    }

    private function storeBulk(Request $request)
    {
        $request->validate([
            'allocations'                => ['required', 'array', 'min:1'],
            'allocations.*.user_id'      => ['required', 'exists:users,id'],
            'allocations.*.product_id'   => ['required', 'exists:products,id'],
            'allocations.*.date'         => ['required', 'date'],
            'allocations.*.qty_given'    => ['required', 'integer', 'min:1'],
        ]);

        $savedCount = 0;

        foreach ($request->allocations as $item) {
            $user = User::find($item['user_id']);

            if ($user->role !== 'penjual') continue;

            $exists = StockAllocation::where('user_id', $item['user_id'])
                ->where('product_id', $item['product_id'])
                ->whereDate('date', $item['date'])
                ->exists();

            if ($exists) continue;

            StockAllocation::create($item);
            $savedCount++;
        }

        return back()->with('success', "$savedCount alokasi massal berhasil disimpan.");
    }

    public function update(Request $request, StockAllocation $stockAllocation)
    {
        $validated = $request->validate([
            'qty_given' => ['required', 'integer', 'min:1'],
        ]);

        $stockAllocation->update($validated);

        return back()->with('success', 'Jumlah stok berhasil diperbarui.');
    }

    public function destroy(StockAllocation $stockAllocation)
    {
        $stockAllocation->delete();

        return back()->with('success', 'Alokasi stok berhasil ditarik dari panci.');
    }

    // Biarkan fungsi ini berbentuk JSON jika nantinya diperlukan oleh frontend teman Anda
    public function myStock(Request $request)
    {
        $userId = auth()->user()->id;
        $query = StockAllocation::with('product:id,name,price')->where('user_id', $userId);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        return response()->json(['data' => $query->orderBy('date', 'desc')->get()]);
    }
}