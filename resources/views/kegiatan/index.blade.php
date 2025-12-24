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

        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</p>
        <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }}</p>
        <p><strong>Lokasi:</strong> {{ $k->lokasi }}</p>
        <p><strong>Jenis Kegiatan:</strong> {{ $k->jenis->nama }}</p>

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
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

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