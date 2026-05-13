<?php

namespace App\Http\Controllers;

use App\Models\PesananShopee;
use App\Models\Product;
use Illuminate\Http\Request;

class PesananShopeeController extends Controller
{
    // Display all pesanan shopee
    public function index()
    {
        $pesanans = PesananShopee::orderBy('created_at', 'desc')->get();
        $products = Product::all();

        return view('pesanan-shopee', [
            'pesanans' => $pesanans,
            'products' => $products
        ]);
    }

    // Store new pesanan shopee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|array',
            'id_produk.*' => 'required|integer|exists:products,id',
            'jumlah_produk' => 'required|array',
            'jumlah_produk.*' => 'required|integer|min:1',
            'jenis' => 'required|in:Instant,SPX,JNE,JNT',
            'nama_pembeli' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        // Calculate total harga jual
        $totalHarga = 0;
        foreach ($validated['id_produk'] as $index => $produkId) {
            $product = Product::find($produkId);
            if ($product) {
                $totalHarga += $product->salesprice1 * $validated['jumlah_produk'][$index];
            }
        }

        $pesanan = PesananShopee::create([
            'id_produk' => $validated['id_produk'],
            'jumlah_produk' => $validated['jumlah_produk'],
            'jenis' => $validated['jenis'],
            'status' => 'BELUM_DIKIRIM',
            'total_harga_jual' => $totalHarga,
            'nama_pembeli' => $validated['nama_pembeli'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'catatan' => $validated['catatan'] ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $pesanan
        ]);
    }

    // Get pesanan detail with product info
    public function show($id)
    {
        $pesanan = PesananShopee::find($id);

        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        // Enrich with product details
        $produkDetails = [];
        foreach ($pesanan->id_produk as $index => $produkId) {
            $product = Product::find($produkId);
            if ($product) {
                $produkDetails[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->salesprice1,
                    'quantity' => $pesanan->jumlah_produk[$index],
                    'subtotal' => $product->salesprice1 * $pesanan->jumlah_produk[$index]
                ];
            }
        }

        return response()->json([
            'pesanan' => $pesanan,
            'produk_details' => $produkDetails
        ]);
    }

    // Update status pesanan to DIKIRIM
    public function updateStatus($id)
    {
        $pesanan = PesananShopee::find($id);

        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        $pesanan->update(['status' => 'DIKIRIM']);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan diperbarui menjadi DIKIRIM',
            'data' => $pesanan
        ]);
    }

    // Get all products for selection
    public function getProducts()
    {
        $products = Product::select('id', 'name', 'salesprice1', 'stock')->get();
        return response()->json($products);
    }
}
