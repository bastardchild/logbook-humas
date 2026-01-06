@extends('layouts.app')

@section('title','Daftar Kegiatan')

@push('styles')
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<style>
    dialog {
        max-width: 700px;
    }
    .tag {
        display:inline-block;
        padding:.25rem .5rem;
        margin:.15rem;
        background:#eee;
        border-radius:4px;
        font-size:.85rem;
    }
    /* Dark mode Grid.js */
    body .gridjs-container, body .gridjs-tbody, body td.gridjs-td {
        background-color: transparent;
        color: #e5e7eb;
    }

    body .gridjs-th {
        background-color: #020617;
        color: #f8fafc;
        border-color: #fff;
    }

    body .gridjs-td {
        border-color: #fff;
    }

    body .gridjs-footer {
        background-color: #020617;
        color: #fff;
    }
    body .gridjs-pagination{
        color: #fff;
    }

    body .gridjs-pagination button {
        background-color: #020617;
        color: #000;
        border-color: #334155;
    }

    /* shuffle.js */
    .kegiatan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
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

@section('content')
<article>
    <header style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('kegiatan.create') }}" class="contrast">
            Tambah Kegiatan
        </a>
    </header>

    <div class="kegiatan-toolbar">
        <input type="search" id="search" placeholder="Cari kegiatan...">
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

                    <footer>
                        <button class="secondary"
                            onclick="document.getElementById('modal-{{ $k->id }}').showModal()">
                            Detail
                        </button>
                    </footer>
                </article>
            </div>
        @endforeach
    </div>


    <div id="kegiatan-table"></div>

    @if($data->isEmpty())
        <p>Tidak ada kegiatan.</p>
    @endif
</article>

{{-- MODAL DETAIL --}}
@foreach($data as $k)
<dialog id="modal-{{ $k->id }}">
    <article>
        <h2>{{ $k->nama_kegiatan }}</h2>

        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</p><hr>
        <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }}</p><hr>
        <p><strong>Lokasi:</strong> {{ $k->lokasi }}</p><hr>
        <p><strong>Jenis Kegiatan:</strong> {{ $k->jenis->nama }}</p><hr>

        <!-- <p>
            <strong>Anggota:</strong><br>
            @foreach($k->anggota as $ang)
                <span class="tag">{{ $ang->nama }}</span>
            @endforeach
        </p> -->

        <p>
            <strong>Dokumentasi:</strong><br>
            @if($k->link_drive)
                <a href="{{ $k->link_drive }}" target="_blank" rel="noopener">
                    Google Drive
                </a>
            @elseif($k->surat_tugas)
                <a href="{{ asset('storage/'.$k->surat_tugas) }}" target="_blank">
                    Surat Tugas (PDF)
                </a>
            @else
                Tidak ada
            @endif
        </p>

        @if($k->catatan)
            <p><strong>Catatan:</strong> {{ $k->catatan }}</p>
        @endif

        <footer style="display:flex; justify-content:space-between; gap:.5rem;">
            <a href="{{ route('kegiatan.edit',$k->id) }}" class="contrast">
                Edit
            </a>

            <button class="secondary"
                onclick="document.getElementById('modal-{{ $k->id }}').close()">
                Tutup
            </button>
        </footer>
    </article>
</dialog>
@endforeach
@endsection

@push('scripts')
<script src="https://unpkg.com/shufflejs@5/dist/shuffle.min.js"></script>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const data = [
        @php $no = 1; @endphp
        @foreach($data as $k)
        [
            {{ $no++ }}, // nomor urut
            "{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }}",
            "{{ addslashes($k->nama_kegiatan) }}",
            "{{ addslashes($k->jenis->nama) }}",
            "{{ addslashes($k->lokasi) }}",
            {{ $k->id }}
        ],
        @endforeach
    ];

    new gridjs.Grid({
        columns: [
            { name: "No", sort: false, width: "75px" },        // kolom No
            { name: "Tanggal" },
            { name: "Nama Kegiatan" },
            { name: "Jenis" },
            { name: "Lokasi" },
            { name: "ID", hidden: true },
            {
                name: "Aksi",
                sort: false,
                formatter: (_, row) => {
                    const id = row.cells[5].data; // ID tetap index terakhir
                    return gridjs.html(`
                        <button class="secondary"
                            onclick="document.getElementById('modal-${id}').showModal()">
                            Detail
                        </button>
                    `);
                }
            }
        ],
        data: data,
        search: true,
        sort: true,
        pagination: {
            enabled: true,
            limit: 10
        },
        language: {
            search: { placeholder: "Cari kegiatan..." },
            pagination: {
                previous: "Prev",
                next: "Next",
                showing: "Menampilkan",
                results: () => "data"
            }
        }
    }).render(document.getElementById("kegiatan-table"));

});
</script>
@endpush