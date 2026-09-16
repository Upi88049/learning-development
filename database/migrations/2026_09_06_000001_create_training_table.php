<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel dasar "training". Kolom tambahan (kode_training, scope_training,
     * gambar, deskripsi_training) serta perubahan nullable pada
     * mandatory_training & gol_training ditangani oleh migration setelah ini
     * (2026_09_07, 2026_09_08, 2026_09_15).
     */
    public function up(): void
    {
        if (Schema::hasTable('training')) {
            return;
        }

        Schema::create('training', function (Blueprint $table) {
            $table->increments('id_training');
            $table->string('jenis_training', 255)->nullable();
            $table->string('nama_training', 255);
            $table->string('mandatory_training', 255);
            $table->string('gol_training', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training');
    }
};
