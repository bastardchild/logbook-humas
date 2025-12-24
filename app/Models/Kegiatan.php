<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'jam',
        'lokasi',
        'jenis_kegiatan_id',
        'surat_tugas',
        'link_drive',
        'link_website',
        'catatan'
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }

    public function anggota()
    {
        return $this->belongsToMany(Anggota::class, 'kegiatan_anggota');
    }
}



