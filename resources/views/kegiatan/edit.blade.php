@extends('layouts.app')

@section('title','Edit Kegiatan')

@section('content')
<article>
    <h1>Edit Kegiatan</h1>

    <form action="{{ route('kegiatan.update', $kegiatan->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>
            Nama Kegiatan
            <input type="text" name="nama_kegiatan"
                   value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}">
        </label>

        <label>
            Tanggal
            <input type="date" name="tanggal"
                   value="{{ old('tanggal', $kegiatan->tanggal) }}">
        </label>

        <label class="form-label">Jam
            <input type="time" name="jam"            
            value="{{ old('jam', $kegiatan->jam) }}">
        </label>

        <label>
            Lokasi
            <input type="text" name="lokasi"
                   value="{{ old('lokasi', $kegiatan->lokasi) }}">
        </label>

        <label>
            Jenis Kegiatan
            <select name="jenis_kegiatan_id">
                @foreach($jenisKegiatan as $jk)
                    <option value="{{ $jk->id }}"
                        @selected($jk->id == $kegiatan->jenis_kegiatan_id)>
                        {{ $jk->nama }}
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            Surat Tugas (PDF)
            <input type="file" name="surat_tugas">
            @if($kegiatan->surat_tugas)
                <small>
                    File lama:
                    <a href="{{ asset('storage/'.$kegiatan->surat_tugas) }}"
                       target="_blank">Lihat</a>
                </small>
            @endif
        </label>

        <label>
            Link Dokumentasi (Google Drive)
            <input type="url" name="link_drive"
                placeholder="https://drive.google.com/..."
                value="{{ old('link_drive', $kegiatan->link_drive) }}">
        </label>

        <label for="link_website">Link Publikasi (website) - Opsional</label>
        <input id="link_website" name="link_website" type="url" value="{{ old('link_website', $kegiatan->link_website) }}">

        <label>
            Catatan
            <textarea name="catatan" rows="3"
                placeholder="Catatan tambahan kegiatan...">{{ old('catatan', $kegiatan->catatan) }}</textarea>
        </label>        

        <!-- <fieldset>
            <legend>Pilih Anggota Bertugas</legend>
            @foreach($anggota as $a)
                <label class="contrast">
                    <input type="checkbox"
                        name="anggota[]"
                        value="{{ $a->id }}"
                        @checked($kegiatan->anggota->contains($a->id))>
                    {{ $a->nama }}
                </label>
            @endforeach
        </fieldset> -->

        <button type="submit">
            Update
        </button>
        <a href="{{ route('kegiatan.index') }}" class="secondary">
            Batal
        </a>
    </form>
</article>
@endsection
