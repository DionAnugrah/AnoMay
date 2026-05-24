<?php
// app/Http/Controllers/LaporJualanController.php

namespace App\Http\Controllers;

use App\Http\Requests\LaporJualanRequest;
use App\Models\DailyReport;
use App\Models\StockAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporJualanController extends Controller
{
    // =========================================================
    // BAGIAN PENJUAL
    // =========================================================

    public function store(LaporJualanRequest $request)
    {
        $penjual = Auth::user();
        $alokasi = StockAllocation::with('product')->findOrFail($request->stock_allocation_id);

        if ($alokasi->user_id !== $penjual->id) {
            return $request->wantsJson() 
                ? response()->json(['error' => 'Alokasi stok ini bukan milik Anda.'], 403) 
                : back()->with('error', 'Alokasi stok ini bukan milik Anda.');
        }

        if ($request->qty_sold > $alokasi->qty_given) {
            return $request->wantsJson() 
                ? response()->json(['error' => 'Jumlah terjual melebihi stok yang dibawa.'], 422) 
                : back()->with('error', 'Jumlah terjual melebihi stok yang dibawa.');
        }

        $sudahLapor = DailyReport::where('stock_allocation_id', $alokasi->id)->exists();
        if ($sudahLapor) {
            return $request->wantsJson() 
                ? response()->json(['error' => 'Produk ini sudah dilaporkan untuk tanggal tersebut.'], 422) 
                : back()->with('error', 'Produk ini sudah dilaporkan untuk tanggal tersebut.');
        }

        $qtySold      = $request->qty_sold;
        $qtyReturned  = $alokasi->qty_given - $qtySold;
        $totalDeposit = $qtySold * $alokasi->product->price;

        $report = DailyReport::create([
            'user_id'             => $penjual->id,
            'stock_allocation_id' => $alokasi->id,
            'date'                => $alokasi->date,
            'qty_sold'            => $qtySold,
            'qty_returned'        => $qtyReturned,
            'total_deposit'       => $totalDeposit,
            'status'              => 'pending',
        ]);

        return $request->wantsJson() 
            ? response()->json(['message' => 'Laporan berhasil disimpan.', 'data' => $report], 201) 
            : back()->with('success', 'Laporan berhasil disimpan.');
    }

    public function riwayat(Request $request)
    {
        $reports = DailyReport::where('user_id', Auth::user()->id)
                    ->orderBy('date', 'desc')
                    ->get();

        if ($request->wantsJson()) {
            return response()->json($reports);
        }

        // SESUAIKAN: Mengarah ke folder Resourceful penjual yang baru
        return view('penjual.laporan.index', ['reports' => $reports]);
    }

    // =========================================================
    // BAGIAN ADMIN
    // =========================================================

    public function indexAdmin(Request $request)
    {
        $query = DailyReport::with(['user', 'stockAllocation.product']);

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('date', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json($reports);
        }

        // SESUAIKAN: Mengarah ke folder Resourceful admin yang baru
        return view('admin.laporan.index', ['reports' => $reports]);
    }

    public function showAdmin(DailyReport $report)
    {
        return response()->json($report->load('user'));
    }

    public function update(Request $request, DailyReport $report)
    {
        $request->validate([
            'qty_sold' => 'required|integer|min:0',
            'date'     => 'sometimes|date',
        ]);

        $alokasi = StockAllocation::where('user_id', $report->user_id)
                        ->where('date', $report->date)
                        ->first();

        if (!$alokasi) {
            return $request->wantsJson() 
                ? response()->json(['error' => 'Data alokasi tidak ditemukan.'], 404) 
                : back()->with('error', 'Data alokasi stok untuk laporan ini tidak ditemukan.');
        }

        if ($request->qty_sold > $alokasi->qty_given) {
            return $request->wantsJson() 
                ? response()->json(['error' => 'Melebihi stok dibawa.'], 422) 
                : back()->with('error', 'Jumlah terjual melebihi stok yang dibawa.');
        }

        $qtySold      = $request->qty_sold;
        $qtyReturned  = $alokasi->qty_given - $qtySold;
        $totalDeposit = $qtySold * $alokasi->product->price;

        $report->update([
            'qty_sold'      => $qtySold,
            'qty_returned'  => $qtyReturned,
            'total_deposit' => $totalDeposit,
        ]);

        return $request->wantsJson() 
            ? response()->json(['message' => 'Laporan diperbarui.', 'data' => $report]) 
            : back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, DailyReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted',
        ]);

        $report->update(['status' => $request->status]);

        return $request->wantsJson() 
            ? response()->json(['message' => 'Status diubah.', 'data' => $report]) 
            : back()->with('success', 'Status laporan berhasil diubah.');
    }

    public function destroy(Request $request, DailyReport $report)
    {
        $report->delete();

        return $request->wantsJson() 
            ? response()->json(['message' => 'Laporan dihapus.']) 
            : back()->with('success', 'Laporan berhasil dihapus.');
    }
}