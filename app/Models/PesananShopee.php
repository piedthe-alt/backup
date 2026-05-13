<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananShopee extends Model
{
    protected $connection = 'u990824557_db_app';
    protected $table = 'pesanan_shopee';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_produk',
        'jumlah_produk',
        'status',
        'jenis',
        'total_harga_jual',
        'nama_pembeli',
        'alamat',
        'catatan'
    ];

    protected $casts = [
        'id_produk' => 'array',
        'jumlah_produk' => 'array',
        'total_harga_jual' => 'decimal:2'
    ];
}
