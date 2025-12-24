<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKegiatanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jenis_kegiatan')->insert([
            ['nama' => 'Internal'],
            ['nama' => 'Eksternal']            
        ]);
    }
}