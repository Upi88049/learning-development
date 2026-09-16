<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel master organisasi: divisi, department, dan level_jabatan.
     * Migration ini harus berjalan sebelum tabel staff (2026_08_26) dan
     * sebelum migration 2026_08_31 yang meng-ALTER tabel department.
     */
    public function up(): void
    {
        if (!Schema::hasTable('divisi')) {
            Schema::create('divisi', function (Blueprint $table) {
                $table->increments('id_divisi');
                $table->string('nama_divisi', 255);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('department')) {
            Schema::create('department', function (Blueprint $table) {
                $table->increments('id_department');
                $table->integer('id_divisi')->nullable();
                $table->string('nama_department', 255);
                $table->timestamps();

                $table->index('id_divisi');
            });
        }

        if (!Schema::hasTable('level_jabatan')) {
            Schema::create('level_jabatan', function (Blueprint $table) {
                $table->increments('id_level_jabatan');
                $table->string('kode_level_jabatan', 50)->nullable();
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_jabatan');
        Schema::dropIfExists('department');
        Schema::dropIfExists('divisi');
    }
};
