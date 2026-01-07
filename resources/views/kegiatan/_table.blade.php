@push('styles')
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<style>    
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
        color: var(--pico-color);
    }

    body .gridjs-th {
        background-color: var(--pico-background-color);
        color: var(--pico-color);
        border-color: var(--pico-h1-color);
    }

    body .gridjs-td {
        border-color: var(--pico-h1-color);
    }

    body .gridjs-footer {
        background-color: var(--pico-background-color);
        color: var(--pico-color);
    }
    body .gridjs-pagination{
        color: var(--pico-color);
    }

    body .gridjs-pagination button {
        background-color: var(--pico-background-color);
        color: #000;
        border-color: #334155;
    }
    body .gridjs-wrapper {
        border: 1px solid var(--pico-form-element-border-color);       
    }  
</style>
@endpush


<div id="kegiatan-table"></div>

@if($data->isEmpty())
    <p>Tidak ada kegiatan.</p>
@endif


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
                            <button class="btn btn-danger"
                                onclick="hapusKegiatan(${id})">
                                Hapus
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