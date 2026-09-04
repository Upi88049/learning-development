<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penugasan_training', function (Blueprint $table) {
            $table->string('nama_direktur')->nullable()->after('tempat_tanggal_persetujuan');
            $table->string('jabatan_direktur')->default('Director')->after('nama_direktur');
            $table->string('nama_im')->nullable()->after('jabatan_direktur');
            $table->string('bagian_im')->nullable()->after('nama_im');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penugasan_training', function (Blueprint $table) {
            $table->dropColumn(['nama_direktur', 'jabatan_direktur', 'nama_im', 'bagian_im']);
        });
    }
};
