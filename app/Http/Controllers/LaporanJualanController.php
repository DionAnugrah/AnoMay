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

    /**
     * Penjual simpan laporan jualan hari ini.
     */
    public function store(LaporJualanRequest $request)
    {
        $penjual = Auth::user();
        $alokasi = StockAllocation::findOrFail($request->stock_allocation_id);
        $produk  = $alokasi->product;

        if ($alokasi->user_id !== $penjual->id) {
            return response()->json([
                'message' => 'Alokasi stok ini bukan milik Anda.'
            ], 403);
        }

        if ($request->qty_sold > $alokasi->qty_given) {
            return response()->json([
                'message'     => 'Jumlah terjual melebihi stok yang dibawa hari ini.',
                'stok_dibawa' => $alokasi->qty_given,
            ], 422);
        }

        $sudahLapor = DailyReport::where('user_id', $penjual->id)
                        ->where('date', $alokasi->date)
                        ->exists();

        if ($sudahLapor) {
            return response()->json([
                'message' => 'Kamu sudah melaporkan jualan untuk hari ini.'
            ], 422);
        }

        // KALKULASI OTOMATIS
        $qtySold      = $request->qty_sold;
        $qtyReturned  = $alokasi->qty_given - $qtySold;
        $totalDeposit = $qtySold * $produk->price;

        $laporan = DailyReport::create([
            'user_id'       => $penjual->id,
            'date'          => $alokasi->date,
            'qty_sold'      => $qtySold,
            'qty_returned'  => $qtyReturned,
            'total_deposit' => $totalDeposit,
            'status'        => 'pending',
        ]);

        return response()->json([
            'message'       => 'Laporan jualan berhasil disimpan.',
            'qty_sold'      => $qtySold,
            'qty_returned'  => $qtyReturned,
            'total_deposit' => 'Rp ' . number_format($totalDeposit, 0, ',', '.'),
            'data'          => $laporan,
        ], 201);
    }

    /**
     * Penjual lihat riwayat laporan jualannya sendiri.
     */
    public function riwayat()
    {
        $data = DailyReport::where('user_id', Auth::id())
                    ->orderBy('date', 'desc')
                    ->get();

        return response()->json($data);
    }

    // =========================================================
    // BAGIAN ADMIN
    // =========================================================

    /**
     * Admin lihat semua laporan (bisa filter by tanggal / penjual).
     */
    public function indexAdmin(Request $request)
    {
        $query = DailyReport::with('user');

        // Filter by tanggal
        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        // Filter by penjual
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('date', 'desc')->get();

        return response()->json($data);
    }

    /**
     * Admin lihat detail satu laporan.
     */
    public function showAdmin(DailyReport $report)
    {
        return response()->json($report->load('user'));
    }

    /**
     * Admin edit laporan jika ada kesalahan input.
     * Sistem otomatis hitung ulang qty_returned & total_deposit.
     */
    public function update(Request $request, DailyReport $report)
    {
        $request->validate([
            'qty_sold' => 'required|integer|min:0',
            'date'     => 'sometimes|date',
        ]);

        // Ambil alokasi stok berdasarkan user & tanggal laporan
        $alokasi = StockAllocation::where('user_id', $report->user_id)
                        ->where('date', $report->date)
                        ->first();

        if (!$alokasi) {
            return response()->json([
                'message' => 'Data alokasi stok untuk laporan ini tidak ditemukan.'
            ], 404);
        }

        if ($request->qty_sold > $alokasi->qty_given) {
            return response()->json([
                'message'     => 'Jumlah terjual melebihi stok yang dibawa.',
                'stok_dibawa' => $alokasi->qty_given,
            ], 422);
        }

        // KALKULASI ULANG OTOMATIS
        $qtySold      = $request->qty_sold;
        $qtyReturned  = $alokasi->qty_given - $qtySold;
        $totalDeposit = $qtySold * $alokasi->product->price;

        $report->update([
            'qty_sold'      => $qtySold,
            'qty_returned'  => $qtyReturned,
            'total_deposit' => $totalDeposit,
        ]);

        return response()->json([
            'message'       => 'Laporan berhasil diperbarui.',
            'qty_sold'      => $qtySold,
            'qty_returned'  => $qtyReturned,
            'total_deposit' => 'Rp ' . number_format($totalDeposit, 0, ',', '.'),
            'data'          => $report,
        ]);
    }

    /**
     * Admin ubah status laporan (pending → accepted).
     */
    public function updateStatus(Request $request, DailyReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted',
        ]);

        $report->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status laporan berhasil diubah.',
            'data'    => $report,
        ]);
    }

    /**
     * Admin hapus laporan jika ada kesalahan atau data duplikat.
     */
    public function destroy(DailyReport $report)
    {
        $report->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }
}