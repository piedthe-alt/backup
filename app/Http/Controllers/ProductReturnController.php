<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReturnController extends Controller
{
    public function index()
    {
        // RETUR dari APP DB
        $returns = DB::connection('u990824557_db_app')
            ->table('product_returns')
            ->orderByDesc('id')
            ->get();

        // PRODUCT + GROUP dari MASTER DB
        $products = DB::table('product')
            ->leftJoin('productgroup', 'product.productgroup', '=', 'productgroup.id')
            ->select(
                'product.id',
                'product.name',
                'product.productgroup',
                'productgroup.name as group_name'
            )
            ->get()
            ->keyBy('id');

        // mapping manual
        foreach ($returns as $r) {

            $product = $products[$r->product_id] ?? null;

            $r->product_id_view = $r->product_id;
            $r->product_name = $product->name ?? '-';
            $r->group_name = $product->group_name ?? '-';
        }

        return view('return', compact('returns'));
    }

    public function store(Request $request)
    {
        DB::connection('mysql_app')
            ->table('product_returns')
            ->insert([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'note' => $request->note,
                'status' => 'BELUM_DIAMBIL',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect('/return')->with('success', 'Retur berhasil disimpan');
    }
}
