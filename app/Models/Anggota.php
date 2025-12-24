<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = ['nama', 'kontak'];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_anggota');
    }
}

