<!-- FORM SEARCH -->
<form method="GET" action="/" class="mb-5">

    <div class="row g-3 mb-4">

        <!-- SEARCH -->
        <div class="col-md-6">
            <input type="text" name="keyword" id="searchInput" class="form-control search-input"
                placeholder="🔍 Cari nama produk atau scan barcode..." value="{{ request('keyword') }}"
                autofocus>
        </div>

        <!-- FILTER GROUP -->
        <div class="col-md-3">
            <div class="position-relative">
                <!-- INPUT SEARCH -->
                <input
                    type="text"
                    id="groupSearch"
                    class="form-control search-input"
                    placeholder="📂 Cari Group..."
                    autocomplete="off"
                    value="@php
                        $selectedGroup = $productgroups->firstWhere('id', request('productgroup'));
                        echo $selectedGroup ? $selectedGroup->name : '';
                    @endphp"
                >

                <!-- HIDDEN -->
                <input
                    type="hidden"
                    name="productgroup"
                    id="selectedGroup"
                    value="{{ request('productgroup') }}"
                >

                <!-- DROPDOWN -->
                <div
                    id="groupDropdown"
                    class="bg-white border rounded shadow-sm"
                    style="
                        position:absolute;
                        top:100%;
                        left:0;
                        right:0;
                        max-height:300px;
                        overflow-y:auto;
                        z-index:9999;
                        display:none;
                    "
                >

                    <!-- SEMUA -->
                    <div
                        class="group-item p-2 border-bottom"
                        data-id=""
                        data-name="Semua Group"
                        style="cursor:pointer;"
                    >
                        📂 Semua Group
                    </div>

                    <!-- GROUP -->
                    @foreach ($productgroups as $group)

                        <div
                            class="group-item p-2 border-bottom"
                            data-id="{{ $group->id }}"
                            data-name="{{ strtolower($group->name) }}"
                            data-label="{{ $group->name }}"
                            style="cursor:pointer;"
                        >
                            {{ $group->name }}
                        </div>

                    @endforeach

                </div>

            </div>
        </div>

        <!-- BUTTON -->
        <div class="col-md-3">
            <button type="submit" class="btn btn-action btn-primary w-100">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>

    </div>

</form>

<script>
    document.addEventListener('DOMContentLoaded', function(){

        const searchInput = document.getElementById('groupSearch');
        const dropdown = document.getElementById('groupDropdown');
        const hiddenInput = document.getElementById('selectedGroup');
        const items = document.querySelectorAll('.group-item');

        /* SHOW DROPDOWN */
        searchInput.addEventListener('focus', function(){
            dropdown.style.display = 'block';
        });

        /* SEARCH */
        searchInput.addEventListener('keyup', function(){
            const keyword = this.value.toLowerCase();
            dropdown.style.display = 'block';

            items.forEach(item => {
                const name = item.dataset.name;
                if(name.includes(keyword)){
                    item.style.display = 'block';
                }else{
                    item.style.display = 'none';
                }
            });
        });

        /* SELECT ITEM */
        items.forEach(item => {
            item.addEventListener('click', function(){
                hiddenInput.value = this.dataset.id;
                searchInput.value = this.dataset.label || 'Semua Group';
                dropdown.style.display = 'none';

                /* AUTO SUBMIT FORM */
                const form = searchInput.closest('form');
                if(form){
                    form.submit();
                }
            });

            /* HOVER */
            item.addEventListener('mouseenter', function(){
                this.style.background = '#f8fafc';
            });

            item.addEventListener('mouseleave', function(){
                this.style.background = 'white';
            });

        });

        /* CLOSE OUTSIDE */
        document.addEventListener('click', function(e){
            if(!e.target.closest('.position-relative')){
                dropdown.style.display = 'none';
            }
        });

    });
</script>
