# 🎯 Implementation Best Practices & Patterns

## Mobile-First Responsive Grid System

### Responsive Grid Auto-adjust

```blade
<!-- Product Grid - Auto responsive -->
<div class="grid gap-md" style="
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
">
    @foreach ($products as $product)
        <div class="card">
            <!-- Card content -->
        </div>
    @endforeach
</div>

<!-- Atau menggunakan CSS media query -->
<style>
    .product-grid {
        display: grid;
        gap: var(--spacing-md);
        grid-template-columns: repeat(4, 1fr);
    }
    
    @media (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
```

---

## Header & Page Title

### Modern Page Header

```blade
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="fas fa-box"></i>
            Manajemen Produk
        </h1>
        <p class="page-subtitle">Kelola dan pantau inventory produk secara real-time</p>
    </div>
</div>

<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        color: white;
        padding: var(--spacing-xl) var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }
    
    .page-header-content {
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 640px) {
        .page-header {
            padding: var(--spacing-lg) var(--spacing-md);
        }
        
        .page-title {
            font-size: var(--font-size-2xl);
        }
    }
</style>
```

---

## Search & Filter Bar

### Modern Search Component

```blade
<div class="card mb-lg">
    <div class="card-body">
        <form method="GET" action="/products" class="row g-md-3">
            <!-- Search Input -->
            <div class="col-12 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control" 
                        placeholder="Cari nama produk atau barcode..."
                        value="{{ request('keyword') }}"
                    >
                </div>
            </div>
            
            <!-- Category Filter -->
            <div class="col-12 col-sm-6 col-lg-3">
                <select name="category" class="form-select">
                    <option value="">📂 Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Submit Button -->
            <div class="col-12 col-sm-6 col-lg-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>
```

---

## Product Card - Modern Design

### Premium Product Card

```blade
<div class="card h-100 d-flex flex-column">
    <!-- Image Container -->
    <div style="
        background: linear-gradient(135deg, var(--slate-100) 0%, var(--slate-200) 100%);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    ">
        @if(isset($product->image))
            <img 
                src="{{ $product->image }}" 
                alt="{{ $product->name }}" 
                style="width: 100%; height: 100%; object-fit: cover;"
            >
        @else
            <i class="fas fa-image" style="font-size: 3rem; color: var(--slate-400);"></i>
        @endif
        
        <!-- Stock Badge -->
        @if($product->stock > 20)
            <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                <span class="badge badge-success">
                    <i class="fas fa-check"></i> Aman
                </span>
            </div>
        @elseif($product->stock > 5)
            <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                <span class="badge badge-warning">
                    <i class="fas fa-exclamation"></i> Menipis
                </span>
            </div>
        @else
            <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                <span class="badge badge-danger">
                    <i class="fas fa-alert"></i> Kritis
                </span>
            </div>
        @endif
    </div>
    
    <!-- Card Body -->
    <div class="card-body flex-grow-1">
        <!-- Title -->
        <h5 class="mb-sm" style="line-height: var(--line-height-tight);">
            {{ Str::limit($product->name, 40) }}
        </h5>
        
        <!-- Price -->
        <div class="mb-md">
            <small class="text-muted">Harga Jual</small>
            <div class="text-primary font-bold" style="font-size: var(--font-size-xl);">
                Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
            </div>
        </div>
        
        <!-- Info Row -->
        <div class="flex flex-between mb-md" style="gap: var(--spacing-sm);">
            <div>
                <small class="text-muted">Stock</small>
                <div class="font-semibold">{{ $product->stock }}</div>
            </div>
            <div class="text-right">
                <small class="text-muted">Masuk</small>
                <div class="font-semibold text-success">{{ $product->total_masuk }}</div>
            </div>
            <div class="text-right">
                <small class="text-muted">Keluar</small>
                <div class="font-semibold text-warning">{{ $product->total_keluar }}</div>
            </div>
        </div>
    </div>
    
    <!-- Card Footer -->
    <div class="card-footer bg-slate-50">
        <div class="flex gap-sm">
            <button class="btn btn-primary btn-sm flex-1">
                <i class="fas fa-eye"></i> Lihat
            </button>
            <button class="btn btn-secondary btn-sm">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>
</div>
```

---

## Data Table - Responsive & Modern

### Complete Table Component

```blade
<!-- Responsive Table Wrapper -->
<div class="card mt-lg">
    <div class="card-header bg-slate-50">
        <h6 class="mb-0">Daftar Produk</h6>
    </div>
    
    <div style="overflow-x: auto; border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
        <table class="table table-hover mb-0">
            <thead>
                <tr style="background-color: var(--slate-50);">
                    <th style="width: 50px;">
                        <input type="checkbox" class="form-check-input">
                    </th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: center;">Stock</th>
                    <th>Status</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr style="border-bottom: 1px solid var(--slate-200);">
                        <td>
                            <input type="checkbox" class="form-check-input">
                        </td>
                        <td class="font-semibold">{{ $product->name }}</td>
                        <td>{{ $product->category_name }}</td>
                        <td style="text-align: right;">
                            <span class="text-primary font-bold">
                                Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="font-semibold">{{ $product->stock }}</span>
                        </td>
                        <td>
                            @if($product->stock > 20)
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Aman
                                </span>
                            @elseif($product->stock > 5)
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation"></i> Menipis
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-triangle-exclamation"></i> Kritis
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div class="flex" style="justify-content: center; gap: var(--spacing-xs);">
                                <button class="btn btn-icon btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-icon btn-sm" title="Hapus">
                                    <i class="fas fa-trash" style="color: var(--danger-500);"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-lg">
                            <i class="fas fa-inbox" style="font-size: 2rem; color: var(--slate-300); display: block; margin-bottom: var(--spacing-md);"></i>
                            <span class="text-muted">Tidak ada data produk</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

---

## Form - Modern Input Styling

### Complete Form Example

```blade
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Produk</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="/products/store">
            @csrf
            
            <div class="row">
                <!-- Nama Produk -->
                <div class="col-12 col-lg-6 mb-lg">
                    <label class="form-label">Nama Produk *</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Masukkan nama produk"
                        value="{{ old('name') }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Kategori -->
                <div class="col-12 col-lg-6 mb-lg">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Harga -->
                <div class="col-12 col-lg-6 mb-lg">
                    <label class="form-label">Harga Jual *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input 
                            type="number" 
                            name="price" 
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="0"
                            value="{{ old('price') }}"
                            required
                        >
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Stock -->
                <div class="col-12 col-lg-6 mb-lg">
                    <label class="form-label">Stock *</label>
                    <input 
                        type="number" 
                        name="stock" 
                        class="form-control @error('stock') is-invalid @enderror"
                        placeholder="0"
                        value="{{ old('stock') }}"
                        required
                    >
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Deskripsi -->
                <div class="col-12 mb-lg">
                    <label class="form-label">Deskripsi</label>
                    <textarea 
                        name="description" 
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan deskripsi produk..."
                    >{{ old('description') }}</textarea>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex gap-md">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <a href="/products" class="btn btn-outline-primary ms-auto">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
```

---

## Modal - Modern Dialog

### Complete Modal Example

```blade
<!-- Trigger Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
    <i class="fas fa-eye"></i> Lihat Detail
</button>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Detail Produk</h5>
                    <small class="text-muted">Informasi lengkap produk</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Nama Produk</small>
                            <div class="font-bold">{{ $product->name }}</div>
                        </div>
                        
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Kategori</small>
                            <div>{{ $product->category_name }}</div>
                        </div>
                        
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Harga</small>
                            <div class="text-primary font-bold" style="font-size: var(--font-size-xl);">
                                Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Stock Saat Ini</small>
                            <div class="font-bold text-lg">{{ $product->stock }} Unit</div>
                        </div>
                        
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Total Masuk</small>
                            <div class="text-success font-bold">{{ $product->total_masuk }}</div>
                        </div>
                        
                        <div class="mb-lg">
                            <small class="text-muted text-uppercase font-semibold">Total Keluar</small>
                            <div class="text-warning font-bold">{{ $product->total_keluar }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## Stat Cards - Dashboard

### KPI/Stats Display

```blade
<div class="grid gap-lg" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
    <!-- Stat Card 1 -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-between mb-md">
                <div>
                    <small class="text-muted text-uppercase font-semibold">Total Produk</small>
                    <h3 class="mb-0 mt-sm">1,234</h3>
                </div>
                <div style="
                    width: 50px;
                    height: 50px;
                    border-radius: var(--radius-lg);
                    background: var(--primary-100);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <i class="fas fa-box" style="color: var(--primary-600); font-size: 1.5rem;"></i>
                </div>
            </div>
            <small class="text-success">
                <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
            </small>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-between mb-md">
                <div>
                    <small class="text-muted text-uppercase font-semibold">Total Terjual</small>
                    <h3 class="mb-0 mt-sm">5,678</h3>
                </div>
                <div style="
                    width: 50px;
                    height: 50px;
                    border-radius: var(--radius-lg);
                    background: var(--success-50);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <i class="fas fa-chart-line" style="color: var(--success-500); font-size: 1.5rem;"></i>
                </div>
            </div>
            <small class="text-success">
                <i class="fas fa-arrow-up"></i> +8% dari minggu lalu
            </small>
        </div>
    </div>
    
    <!-- Stat Card 3 -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-between mb-md">
                <div>
                    <small class="text-muted text-uppercase font-semibold">Total Revenue</small>
                    <h3 class="mb-0 mt-sm">Rp 12.5M</h3>
                </div>
                <div style="
                    width: 50px;
                    height: 50px;
                    border-radius: var(--radius-lg);
                    background: var(--info-50);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <i class="fas fa-dollar-sign" style="color: var(--info-600); font-size: 1.5rem;"></i>
                </div>
            </div>
            <small class="text-success">
                <i class="fas fa-arrow-up"></i> +15% dari bulan lalu
            </small>
        </div>
    </div>
</div>
```

---

## Alert/Notification

### Various Alert Types

```blade
<!-- Info Alert -->
<div class="alert alert-info animate-slideIn">
    <i class="fas fa-info-circle"></i>
    <div>
        <strong>Informasi</strong>
        <p class="mb-0 text-sm">Silakan periksa inventory produk sebelum tutup toko</p>
    </div>
</div>

<!-- Success Alert -->
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <span><strong>Berhasil!</strong> Produk telah disimpan</span>
</div>

<!-- Warning Alert -->
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Perhatian</strong>
        <p class="mb-0 text-sm">Beberapa produk stock menipis. Silakan segera restock.</p>
    </div>
</div>

<!-- Danger Alert -->
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    <span><strong>Error!</strong> Terjadi kesalahan saat menyimpan data</span>
</div>
```

---

## CSS Variables Reference

```css
/* Color Variables */
var(--primary-600)         /* Main primary color */
var(--slate-50)            /* Light background */
var(--slate-800)           /* Dark text */
var(--success-500)         /* Success color */
var(--danger-500)          /* Danger/Error color */
var(--warning-500)         /* Warning color */

/* Spacing Variables */
var(--spacing-sm)          /* Small spacing (12px) */
var(--spacing-md)          /* Medium spacing (16px) */
var(--spacing-lg)          /* Large spacing (24px) */

/* Border Radius Variables */
var(--radius-lg)           /* Large border radius (12px) */
var(--radius-xl)           /* Extra large (16px) */

/* Shadow Variables */
var(--shadow-sm)           /* Small shadow */
var(--shadow-md)           /* Medium shadow */
var(--shadow-lg)           /* Large shadow */

/* Typography Variables */
var(--font-size-base)      /* Base font size (16px) */
var(--font-size-lg)        /* Large font (18px) */
var(--font-size-2xl)       /* Extra large (24px) */
var(--font-weight-bold)    /* Bold weight (700) */

/* Transition Variables */
var(--transition-base)     /* Base transition (200ms) */
```

---

## Common Responsive Patterns

### 1. Desktop-First Fallback

```css
/* Base: Desktop view */
.grid { grid-template-columns: repeat(4, 1fr); }

/* Tablet */
@media (max-width: 1024px) {
    .grid { grid-template-columns: repeat(3, 1fr); }
}

/* Mobile */
@media (max-width: 768px) {
    .grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .grid { grid-template-columns: repeat(1, 1fr); }
}
```

### 2. Flexible Spacing

```blade
<!-- Auto adjust padding -->
<div style="
    padding: var(--spacing-lg);
">
    Content
</div>

<!-- Mobile override -->
@media (max-width: 640px) {
    <div style="padding: var(--spacing-md);"></div>
}
```

### 3. Responsive Typography

```css
/* Base size untuk desktop */
h1 { font-size: var(--font-size-4xl); }

/* Mobile size -->
@media (max-width: 640px) {
    h1 { font-size: var(--font-size-2xl); }
}
```

---

**Version**: 1.0  
**Last Updated**: 14 Mei 2026  
**Status**: Implementation Ready ✅
