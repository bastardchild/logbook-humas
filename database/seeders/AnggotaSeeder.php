<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        DB::table('anggota')->insert([
            ['nama' => 'Akhmad Fajrin'],
            ['nama' => 'Siti Rahma'],
            ['nama' => 'Budi Santoso'],
            ['nama' => 'Nur Aisyah'],
            ['nama' => 'Dewi Kartika'],
        ]);
    }
}