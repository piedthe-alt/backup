<!-- SORTING BUTTONS -->
<div class="sort-buttons mb-5">
    <span class="sort-label">📊 Urutkan Berdasarkan:</span>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_terendah"
        class="sort-btn {{ request('sort') == 'stock_terendah' || !request('sort') ? 'active' : '' }}">
        <i class="fas fa-arrow-down me-1"></i>Stock Terendah
    </a>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_tertinggi"
        class="sort-btn {{ request('sort') == 'stock_tertinggi' ? 'active' : '' }}">
        <i class="fas fa-arrow-up me-1"></i>Stock Tertinggi
    </a>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=paling_laris"
        class="sort-btn {{ request('sort') == 'paling_laris' ? 'active' : '' }}">
        <i class="fas fa-fire me-1"></i>Paling Laris
    </a>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=gak_jalan"
        class="sort-btn {{ request('sort') == 'gak_jalan' ? 'active' : '' }}">
        <i class="fas fa-snooze me-1"></i>Gak Jalan
    </a>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_asc"
        class="sort-btn {{ request('sort') == 'nama_asc' ? 'active' : '' }}">
        <i class="fas fa-sort-alpha-down me-1"></i>A-Z
    </a>

    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_desc"
        class="sort-btn {{ request('sort') == 'nama_desc' ? 'active' : '' }}">
        <i class="fas fa-sort-alpha-up-alt me-1"></i>Z-A
    </a>
</div>
