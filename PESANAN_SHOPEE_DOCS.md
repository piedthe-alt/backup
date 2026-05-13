# Fitur Pesanan Shopee - Dokumentasi

## Deskripsi Fitur
Fitur Pesanan Shopee memungkinkan Anda untuk mencatat dan mengelola pesanan dari Shopee dengan mudah. Anda dapat membuat pesanan baru, melihat detail pesanan, dan mengubah status pesanan menjadi DIKIRIM.

## Struktur Database

### Tabel: `pesanan_shopee`

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| `id_pesanan` | BIGINT UNSIGNED | Primary Key - ID unik pesanan |
| `id_produk` | JSON | Array berisi ID produk yang dipesan |
| `jumlah_produk` | JSON | Array berisi jumlah setiap produk yang dipesan |
| `status` | VARCHAR(255) | Status pesanan (BELUM_DIKIRIM / DIKIRIM) |
| `jenis` | ENUM | Jenis pengiriman (Instant / SPX / JNE / JNT) |
| `total_harga_jual` | DECIMAL(15,2) | Total harga penjualan |
| `nama_pembeli` | VARCHAR(255) | Nama pembeli (opsional) |
| `alamat` | TEXT | Alamat pengiriman (opsional) |
| `catatan` | TEXT | Catatan tambahan (opsional) |
| `created_at` | TIMESTAMP | Waktu pembuatan pesanan |
| `updated_at` | TIMESTAMP | Waktu pembaruan terakhir |

## Setup

### 1. Jalankan Migrasi
```bash
php artisan migrate
```

Atau jika migrasi tidak bisa dijalankan, jalankan SQL secara manual dari file `pesanan_shopee.sql`:
```sql
-- Copy isi dari pesanan_shopee.sql dan jalankan di MySQL
```

### 2. File yang Dibuat/Dimodifikasi

#### New Files:
- `app/Models/PesananShopee.php` - Model untuk tabel pesanan_shopee
- `app/Http/Controllers/PesananShopeeController.php` - Controller untuk CRUD pesanan
- `resources/views/pesanan-shopee.blade.php` - View untuk menampilkan pesanan
- `pesanan_shopee.sql` - SQL untuk membuat tabel

#### Modified Files:
- `routes/web.php` - Ditambahkan route untuk pesanan shopee
- `resources/views/product.blade.php` - Ditambahkan button "Pesanan Shopee" di header

## API Endpoints

### 1. Get All Pesanan (View)
```
GET /pesanan-shopee
```
Menampilkan halaman dengan form dan list pesanan.

### 2. Create Pesanan
```
POST /pesanan-shopee/store
Content-Type: application/json

{
  "id_produk": [1, 2, 3],
  "jumlah_produk": [5, 10, 2],
  "jenis": "JNE",
  "nama_pembeli": "Budi Santoso",
  "alamat": "Jl. Merdeka No. 123",
  "catatan": "Kemasan rapi"
}
```

### 3. Get Pesanan Detail
```
GET /pesanan-shopee/detail/{id}
```
Mengembalikan detail pesanan beserta informasi produk.

### 4. Update Status Pesanan
```
POST /pesanan-shopee/update-status/{id}
```
Mengubah status pesanan menjadi DIKIRIM.

### 5. Get Products (untuk form)
```
GET /api/products
```
Mengembalikan list produk untuk dipilih saat membuat pesanan.

## Fitur-Fitur

### 1. Membuat Pesanan Baru
1. Navigasi ke `/pesanan-shopee`
2. Scroll ke bagian "Buat Pesanan Baru"
3. Klik "Tambah Produk" untuk menambahkan produk
4. Pilih produk dari dropdown dan masukkan jumlah
5. (Opsional) Isi Nama Pembeli, Alamat, dan Catatan
6. Pilih Jenis Pengiriman (Instant/SPX/JNE/JNT)
7. Klik "Simpan Pesanan"

### 2. Melihat Daftar Pesanan
- Semua pesanan ditampilkan dalam bentuk card
- Setiap card menunjukkan:
  - ID Pesanan
  - Tanggal pembuatan
  - Status (BELUM_DIKIRIM / DIKIRIM)
  - Jumlah produk
  - Jenis pengiriman
  - Total harga

### 3. Melihat Detail Pesanan
1. Klik pada card pesanan
2. Modal akan menampilkan:
   - Informasi lengkap pesanan
   - Daftar produk dengan harga dan jumlah
   - Total harga jual
   - Nama pembeli, alamat, dan catatan (jika ada)

### 4. Mengubah Status Pesanan
1. Klik pada card pesanan
2. Pada modal detail, klik tombol "Atur Pick up"
3. Status akan berubah menjadi DIKIRIM
4. Tombol akan hilang jika status sudah DIKIRIM

## Model - PesananShopee

```php
class PesananShopee extends Model
{
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
```

## Controller Methods

### `index()`
Menampilkan halaman pesanan dengan form dan list.

### `store(Request $request)`
Membuat pesanan baru dari data form. Otomatis menghitung total harga jual.

### `show($id)`
Menampilkan detail pesanan beserta info produk yang dipesan.

### `updateStatus($id)`
Mengubah status pesanan menjadi DIKIRIM.

### `getProducts()`
API untuk mendapatkan list produk tersedia.

## Validasi

Form memvalidasi:
- Minimal 1 produk harus dipilih
- Setiap produk harus ada di database
- Jenis pengiriman harus dipilih
- Jumlah produk harus minimal 1

## Catatan Penting

1. **Total Harga Otomatis**: Total harga jual dihitung otomatis berdasarkan harga satuan produk × jumlah
2. **Format JSON**: `id_produk` dan `jumlah_produk` disimpan dalam format JSON untuk fleksibilitas
3. **Status Default**: Status pesanan dibuat dengan default `BELUM_DIKIRIM`
4. **Timestamps**: Setiap pesanan otomatis mencatat waktu pembuatan dan pembaruan

## Troubleshooting

### Database tidak terhubung
- Pastikan MySQL server sudah running
- Cek konfigurasi `.env` file untuk database credentials
- Jalankan migrasi jika belum: `php artisan migrate`

### Produk tidak muncul di dropdown
- Pastikan tabel `product` sudah ada dan memiliki data
- Cek koneksi ke database `mysql`

### Button di product.blade.php tidak muncul
- Refresh halaman (hard refresh Ctrl+F5)
- Bersihkan browser cache

## Customization

Anda bisa mengkustomisasi:
- Jenis pengiriman (tambah/kurangi di ENUM di database dan controller)
- Status pesanan (tambah opsi status baru)
- Field tambahan (tambah kolom di migration dan model)
- Styling (ubah CSS di pesanan-shopee.blade.php)

## Query Contoh

### Get Pesanan berdasarkan Status
```php
$pesananBelumDikirim = PesananShopee::where('status', 'BELUM_DIKIRIM')->get();
$pesananDikirim = PesananShopee::where('status', 'DIKIRIM')->get();
```

### Get Pesanan berdasarkan Jenis
```php
$pesananJNE = PesananShopee::where('jenis', 'JNE')->get();
```

### Get Total Penjualan
```php
$totalPenjualan = PesananShopee::sum('total_harga_jual');
```

### Get Pesanan Hari Ini
```php
$pesananHariIni = PesananShopee::whereDate('created_at', today())->get();
```

## Support & Notes
Jika ada pertanyaan atau butuh modifikasi lebih lanjut, silakan hubungi tim development.
