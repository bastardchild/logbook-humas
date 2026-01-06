document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById("kegiatan-table");
    if (!el) return;

    const rawData = JSON.parse(el.dataset.kegiatan);

    const data = rawData.map(row => ([
        row.no,
        row.tanggal,
        row.nama,
        row.jenis,
        row.lokasi,
        row.id
    ]));

    new gridjs.Grid({
        columns: [
            { name: "No", sort: false, width: "70px" },
            { name: "Tanggal" },
            { name: "Nama Kegiatan" },
            { name: "Jenis" },
            { name: "Lokasi" },
            { name: "ID", hidden: true },
            {
                name: "Aksi",
                sort: false,
                formatter: (_, row) => {
                    const id = row.cells[5].data;
                    return gridjs.html(`
                        <button class="secondary"
                            onclick="document.getElementById('modal-${id}').showModal()">
                            Detail
                        </button>
                    `);
                }
            }
        ],
        data,
        search: true,
        sort: true,
        pagination: { limit: 10 }
    }).render(el);
});
