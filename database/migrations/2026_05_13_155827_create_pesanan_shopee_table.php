<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_shopee', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->json('id_produk')->comment('Array of product IDs');
            $table->json('jumlah_produk')->comment('Array of product quantities');
            $table->string('status')->default('BELUM_DIKIRIM')->comment('BELUM_DIKIRIM or DIKIRIM');
            $table->enum('jenis', ['Instant', 'SPX', 'JNE', 'JNT'])->default('Instant');
            $table->decimal('total_harga_jual', 15, 2);
            $table->string('nama_pembeli')->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_shopee');
    }
};
