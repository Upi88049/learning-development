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
        Schema::dropIfExists('penugasan_training');
        Schema::create('penugasan_training', function (Blueprint $table) {
            $table->increments('id_penugasan');
            $table->string('no_form')->default('Form 013/WI-');
            $table->unsignedInteger('id_request_outhouse')->nullable();
            $table->string('nama_training');
            $table->string('jenis_training')->default('Out House Training');
            $table->string('sub_co')->default('PT. Dharma Polimetal Tbk');
            $table->string('divisi')->nullable();
            $table->longText('peserta_json');
            $table->integer('jumlah_peserta')->default(1);
            $table->decimal('biaya_per_peserta', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->string('terbilang')->nullable();
            $table->text('alasan_pelatihan')->nullable();
            $table->string('nama_atasan')->nullable();
            $table->string('divisi_atasan')->nullable();
            $table->string('jabatan_atasan')->nullable();
            $table->text('tempat_tanggal_training')->nullable();
            $table->string('tempat_tanggal_persetujuan')->nullable();
            $table->string('penyetujui_nama')->nullable();
            $table->string('penyetujui_jabatan')->default('Director');
            $table->string('konfirmasi_nama')->default('Herwin Gultom');
            $table->string('konfirmasi_jabatan')->default('HRGA Deputy Div. Head');
            $table->timestamps();

            $table->foreign('id_request_outhouse')
                ->references('id_request_outhouse')
                ->on('request_outhouse')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_training');
    }
};
