@push('styles')
<style>    
    /* shuffle.js */
    .kegiatan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .kegiatan-item {
        width: 25%;
        padding: 0 0.5rem;
        margin-top: 0.5rem;
    }

    .kegiatan-grid .card {
        padding: 1rem;
        border-radius: 10px;
        background: #020617;
        color: #e5e7eb;
        box-shadow: 0 4px 12px rgba(0,0,0,.2);
    }

    .kegiatan-grid .card header {
        margin-bottom: .5rem;
    }

    .kegiatan-grid .card small {
        display: block;
        color: #94a3b8;
    }

    .kegiatan-grid p{
        font-size: 13px;
    }

    .kegiatan-grid header{
        background: transparent;
    }

    .kegiatan-toolbar {
        margin-bottom: 1rem;
    }

    .kegiatan-toolbar input {
        width: 100%;
        padding: .5rem .75rem;
        border-radius: 6px;
    }

</style>
@endpush

<div class="kegiatan-toolbar pt-3">
    <input type="text" id="search" placeholder="Search kegiatan by name, jenis, or lokasi...">
</div>

<div class="kegiatan-grid" id="kegiatanGrid">
        @foreach($data as $k)
            <div class="kegiatan-item"
                data-title="{{ strtolower($k->nama_kegiatan) }}"
                data-jenis="{{ strtolower($k->jenis->nama) }}"
                data-lokasi="{{ strtolower($k->lokasi) }}">

                <article class="card">
                    <header>
                        <strong>{{ $k->nama_kegiatan }}</strong>
                        <small>
                            {{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}
                            • {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }}
                        </small>
                    </header>

                    <p>
                        <strong>Jenis:</strong> {{ $k->jenis->nama }}<br>
                        <strong>Lokasi:</strong> {{ $k->lokasi }}
                    </p>

                    <footer style="display:flex; gap:8px; align-items:center;">
                        <button class="secondary"
                            onclick="document.getElementById('modal-{{ $k->id }}').showModal()">
                            Detail
                        </button>

                        <button class="btn btn-danger" 
                            onclick="hapusKegiatan({{ $k->id }})">
                            Hapus
                        </button>                       
                    </footer>

                </article>
            </div>
        @endforeach
</div> 
<div class="pagination-wrapper">
    {{ $data->links('vendor.pagination.pico') }}
</div>
    
@push('scripts')
<script src="https://unpkg.com/shufflejs@5/dist/shuffle.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const grid = document.getElementById('kegiatanGrid');

    const shuffleInstance = new Shuffle(grid, {
        itemSelector: '.kegiatan-item',
    });

    document.getElementById('search').addEventListener('keyup', function (e) {
        const searchText = e.target.value.toLowerCase();

        shuffleInstance.filter(function (el) {
            return (
                el.dataset.title.includes(searchText) ||
                el.dataset.jenis.includes(searchText) ||
                el.dataset.lokasi.includes(searchText)
            );
        });
    });

});
</script>
@endpush