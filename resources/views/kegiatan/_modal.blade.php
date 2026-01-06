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

        @if($k->link_website)
            <hr><p>
                <strong>Website:</strong><br>
                <a href="{{ $k->link_website }}" target="_blank" rel="noopener">
                    {{ $k->link_website }}
                </a>
            </p>
        @endif

        @if($k->catatan)
            <hr><p><strong>Catatan:</strong><br> {{ $k->catatan }}</p>
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