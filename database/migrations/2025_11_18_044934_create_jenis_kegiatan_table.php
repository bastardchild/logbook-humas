<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
     public function up() {
        Schema::create('jenis_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        DB::table('jenis_kegiatan')->insert([
            ['nama' => 'Internal', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Eksternal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    public function down() {
        Schema::dropIfExists('jenis_kegiatan');
    }   
};

