@extends('layouts.app')

@section('title','Daftar Kegiatan')

@section('content')
<article>
    <header>
        <h1>Daftar Kegiatan</h1>
        <a href="{{ route('kegiatan.create') }}" class="contrast">Tambah Kegiatan</a>
    </header>

    <ul>
        @foreach($data as $k)
        <li>
            <!-- Tombol trigger modal -->
            <button class="secondary" onclick="document.getElementById('modal-{{ $k->id }}').showModal();">
                <strong>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</strong> | 
                {{ $k->nama_kegiatan }}
            </button>

            <!-- Modal -->
            <dialog id="modal-{{ $k->id }}">
                <article>
                    <h2>{{ $k->nama_kegiatan }}</h2>
                    <p><strong>Tanggal:</strong>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</p>
                    <p><strong>Lokasi:</strong> {{ $k->lokasi }}</p>
                    <p><strong>Jenis Kegiatan:</strong> {{ $k->jenis->nama }}</p>
                    <p><strong>Anggota:</strong> 
                        @foreach($k->anggota as $ang)
                            <span class="tag">{{ $ang->nama }}</span>
                        @endforeach
                    </p>
                    <p>
                        <strong>Dokumentasi:</strong>
                        @if($k->link_drive)
                            <a href="{{ $k->link_drive }}" target="_blank" rel="noopener">Drive</a>
                        @elseif($k->surat_tugas)
                            <a href="{{ asset('storage/' . $k->surat_tugas) }}" target="_blank">Surat Tugas</a>
                        @else
                            Tidak ada
                        @endif
                    </p>
                    @if($k->catatan)
                        <p><strong>Catatan:</strong> {{ $k->catatan }}</p>
                    @endif

                    <footer style="display:flex; gap:.5rem; justify-content:space-between;">
                        <a href="{{ route('kegiatan.edit', $k->id) }}" class="contrast">
                            Edit
                        </a>

                        <button class="secondary"
                            onclick="document.getElementById('modal-{{ $k->id }}').close()">
                            Tutup
                        </button>
                    </footer>
                </article>
            </dialog>
        </li>
        @endforeach
    </ul>

    @if($data->isEmpty())
        <p>Tidak ada kegiatan.</p>
    @endif
</article>
@endsection
