@extends('layouts.app')

@section('title','Tambah Kegiatan')

@section('content')
<article>
  <h1>Tambah Kegiatan</h1>

  <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="nama_kegiatan">Nama Kegiatan</label>
    <input id="nama_kegiatan" name="nama_kegiatan" type="text" required>

    <label for="tanggal">Tanggal</label>
    <input id="tanggal" name="tanggal" type="date" required>
    
    <label for="jam">Jam</label>
    <input type="time" name="jam" required>

    <label for="lokasi">Lokasi</label>
    <input id="lokasi" name="lokasi" type="text" required>

    <label for="jenis_kegiatan_id">Jenis Kegiatan</label>
    <select id="jenis_kegiatan_id" name="jenis_kegiatan_id" required>
        <option value="">-- Pilih Jenis Kegiatan --</option>
        @foreach($jenisKegiatan as $jk)
            <option value="{{ $jk->id }}">{{ $jk->nama }}</option>
        @endforeach
    </select>

    <label for="surat_tugas">Surat Tugas (PDF) - Opsional</label>
    <input id="surat_tugas" name="surat_tugas" type="file" accept="application/pdf">

    <label for="link_dokumentasi">Link Dokumentasi (Google Drive) - Opsional</label>
    <input id="link_dokumentasi" name="link_dokumentasi" type="url" placeholder="https://drive.google.com/...">

    <label for="link_website">Link Publikasi (website) - Opsional</label>
    <input id="link_website" name="link_website" type="url">

    <label for="catatan">Catatan - Opsional</label>
    <textarea id="catatan" name="catatan" rows="4"></textarea>

    <!-- <fieldset>
      <legend>Pilih Anggota Bertugas</legend>
      @foreach($anggota as $a)
        <label class="contrast">
          <input type="checkbox" name="anggota[]" value="{{ $a->id }}">
          {{ $a->nama }}
        </label>
      @endforeach
    </fieldset> -->

    <button type="submit">Simpan</button>
  </form>
</article>
@endsection
