<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PesananShopee;

class PesananShopeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('pesanan-shopee');
    }

    /*
    |--------------------------------------------------------------------------
    | API LIST PESANAN
    |--------------------------------------------------------------------------
    */

    public function apiList()
    {
        $pesanans = DB::connection('mysql_app')
            ->table('pesanan_shopee')
            ->orderByDesc('id_pesanan')
            ->get();

        return response()->json($pesanans);
    }

    /*
    |--------------------------------------------------------------------------
    | GET PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function getProducts()
    {
        $products = DB::table('product')

            ->leftJoin(
                'inventory',
                'product.id',
                '=',
                'inventory.productid'
            )

            ->select(
                'product.id',
                'product.name',
                'product.salesprice1',

                DB::raw('
                    COALESCE(
                        SUM(inventory.invin - inventory.invout),
                        0
                    ) as stock
                ')
            )

            ->where('product.isactive', 1)

            ->groupBy(
                'product.id',
                'product.name',
                'product.salesprice1'
            )

            ->orderBy('product.name')

            ->limit(1000)

            ->get();

        return response()->json($products);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PESANAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        try {

            $request->validate([
                'id_produk' => 'required|array',
                'jumlah_produk' => 'required|array',
                'jenis' => 'required|string'
            ]);

            $totalHarga = 0;

            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL
            |--------------------------------------------------------------------------
            */

            foreach ($request->id_produk as $index => $productId) {

                $product = DB::table('product')
                    ->where('id', $productId)
                    ->first();

                if (!$product) {
                    continue;
                }

                $qty = $request->jumlah_produk[$index] ?? 1;

                $totalHarga +=
                    $product->salesprice1 * $qty;
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            $id = DB::connection('mysql_app')
                ->table('pesanan_shopee')
                ->insertGetId([

                    'id_produk' => json_encode($request->id_produk),

                    'jumlah_produk' => json_encode($request->jumlah_produk),

                    'status' => 'BELUM DIKIRIM',

                    'jenis' => $request->jenis,

                    'total_harga_jual' => $totalHarga,

                    'nama_pembeli' => $request->nama_pembeli,

                    'alamat' => $request->alamat,

                    'catatan' => $request->catatan,

                    'created_at' => now(),

                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'id' => $id
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PESANAN
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        try {

            $pesanan = DB::connection('mysql_app')
                ->table('pesanan_shopee')
                ->where('id_pesanan', $id)
                ->first();

            if (!$pesanan) {

                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | DECODE JSON
            |--------------------------------------------------------------------------
            */

            $idProduk = json_decode($pesanan->id_produk, true) ?? [];

            $jumlahProduk = json_decode($pesanan->jumlah_produk, true) ?? [];

            /*
            |--------------------------------------------------------------------------
            | AMBIL DETAIL PRODUK
            |--------------------------------------------------------------------------
            */

            $produkDetails = [];

            foreach ($idProduk as $index => $productId) {

                $product = DB::table('product')
                    ->where('id', $productId)
                    ->first();

                if (!$product) {
                    continue;
                }

                $qty = $jumlahProduk[$index] ?? 1;

                $subtotal =
                    $product->salesprice1 * $qty;

                $produkDetails[] = [

                    'id' => $product->id,

                    'name' => $product->name,

                    'price' => $product->salesprice1,

                    'quantity' => $qty,

                    'subtotal' => $subtotal
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | FIX ARRAY UNTUK VIEW
            |--------------------------------------------------------------------------
            */

            $pesanan->id_produk = $idProduk;
            $pesanan->jumlah_produk = $jumlahProduk;

            return response()->json([

                'success' => true,

                'pesanan' => $pesanan,

                'produk_details' => $produkDetails

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus($id)
    {
        try {

            DB::connection('mysql_app')
                ->table('pesanan_shopee')
                ->where('id_pesanan', $id)
                ->update([

                    'status' => 'DIKIRIM',

                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }
}
