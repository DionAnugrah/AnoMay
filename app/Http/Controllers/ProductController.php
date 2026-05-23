<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk.
     * GET /admin/products
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();

        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Tampilkan detail satu produk.
     * GET /admin/products/{id}
     */
    public function show(Product $product)
    {
        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Tambah produk baru.
     * POST /admin/products
     * Body: { "name": "Somay Campur", "price": 2000 }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:products,name'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'data'    => $product,
        ], 201);
    }

    /**
     * Edit produk (nama/harga).
     * PUT /admin/products/{id}
     * Body: { "name": "...", "price": 3000 } — semua opsional
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'  => ['sometimes', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'price' => ['sometimes', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diupdate.',
            'data'    => $product->fresh(),
        ]);
    }

    /**
     * Hapus produk.
     * DELETE /admin/products/{id}
     */
    public function destroy(Product $product)
    {
        // Cegah hapus produk yang masih punya stok aktif
        if ($product->stockAllocations()->exists()) {
            return response()->json([
                'message' => 'Produk tidak bisa dihapus karena masih ada data stok yang terkait.',
            ], 422);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}