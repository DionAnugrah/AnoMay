<?php

namespace App\Http\Controllers;

use App\Models\StockAllocation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockAllocationController extends Controller
{
    /**
     * Tampilkan semua alokasi stok (bisa filter by date / user).
     * GET /admin/stock-allocations?date=2026-05-23&user_id=2
     */
    public function index(Request $request)
    {
        $query = StockAllocation::with(['user:id,name', 'product:id,name,price']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json([
            'data' => $query->orderBy('date', 'desc')->get(),
        ]);
    }

    /**
     * Simpan alokasi stok pagi untuk satu atau banyak penjual sekaligus.
     * POST /admin/stock-allocations
     *
     * Body (single):
     * {
     *   "user_id": 2,
     *   "product_id": 1,
     *   "date": "2026-05-23",
     *   "qty_given": 100
     * }
     *
     * Body (bulk — array):
     * {
     *   "allocations": [
     *     { "user_id": 2, "product_id": 1, "date": "2026-05-23", "qty_given": 100 },
     *     { "user_id": 3, "product_id": 1, "date": "2026-05-23", "qty_given": 80 }
     *   ]
     * }
     */
    public function store(Request $request)
    {
        // Deteksi mode bulk atau single
        if ($request->has('allocations')) {
            return $this->storeBulk($request);
        }

        $validated = $request->validate([
            'user_id'    => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'date'       => ['required', 'date'],
            'qty_given'  => ['required', 'integer', 'min:1'],
        ]);

        // Pastikan penjual tidak dapat alokasi duplikat produk di hari yang sama
        $exists = StockAllocation::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Alokasi stok untuk penjual dan produk ini pada tanggal tersebut sudah ada.',
            ], 422);
        }

        // Pastikan user adalah penjual
        $user = User::find($validated['user_id']);
        if (!$user->isPenjual()) {
            return response()->json([
                'message' => 'User yang dipilih bukan penjual.',
            ], 422);
        }

        $allocation = StockAllocation::create($validated);

        return response()->json([
            'message' => 'Alokasi stok berhasil disimpan.',
            'data'    => $allocation->load(['user:id,name', 'product:id,name,price']),
        ], 201);
    }

    /**
     * Simpan banyak alokasi sekaligus.
     */
    private function storeBulk(Request $request)
    {
        $request->validate([
            'allocations'                => ['required', 'array', 'min:1'],
            'allocations.*.user_id'      => ['required', 'exists:users,id'],
            'allocations.*.product_id'   => ['required', 'exists:products,id'],
            'allocations.*.date'         => ['required', 'date'],
            'allocations.*.qty_given'    => ['required', 'integer', 'min:1'],
        ]);

        $saved   = [];
        $skipped = [];

        foreach ($request->allocations as $item) {
            $user = User::find($item['user_id']);

            if (!$user->isPenjual()) {
                $skipped[] = array_merge($item, ['reason' => 'User bukan penjual']);
                continue;
            }

            $exists = StockAllocation::where('user_id', $item['user_id'])
                ->where('product_id', $item['product_id'])
                ->whereDate('date', $item['date'])
                ->exists();

            if ($exists) {
                $skipped[] = array_merge($item, ['reason' => 'Alokasi sudah ada']);
                continue;
            }

            $saved[] = StockAllocation::create($item);
        }

        return response()->json([
            'message' => count($saved) . ' alokasi berhasil disimpan, ' . count($skipped) . ' dilewati.',
            'saved'   => collect($saved)->load(['user:id,name', 'product:id,name,price']),
            'skipped' => $skipped,
        ], 201);
    }

    /**
     * Update qty alokasi stok.
     * PUT /admin/stock-allocations/{id}
     */
    public function update(Request $request, StockAllocation $stockAllocation)
    {
        $validated = $request->validate([
            'qty_given' => ['required', 'integer', 'min:1'],
        ]);

        $stockAllocation->update($validated);

        return response()->json([
            'message' => 'Alokasi stok berhasil diupdate.',
            'data'    => $stockAllocation->fresh()->load(['user:id,name', 'product:id,name,price']),
        ]);
    }

    /**
     * Penjual lihat alokasi stok miliknya filter by tanggal.
     * GET /penjual/my-stock?date=2026-05-23
     */
    public function myStock(Request $request)
    {
        $userId = auth()->user()->id;

        $query = StockAllocation::with('product:id,name,price')
            ->where('user_id', $userId);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        return response()->json([
            'data' => $query->orderBy('date', 'desc')->get(),
        ]);
    }
    public function destroy(StockAllocation $stockAllocation)
    {
        $stockAllocation->delete();

        return response()->json([
            'message' => 'Alokasi stok berhasil dihapus.',
        ]);
    }
}
