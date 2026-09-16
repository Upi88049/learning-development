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
        Schema::dropIfExists('coe_events');
        Schema::create('coe_events', function (Blueprint $table) {
            $table->increments('id_event');
            $table->string('no_event', 50)->unique();
            $table->integer('id_training')->nullable();
            $table->string('nama_training');
            $table->string('batch_training', 100);
            $table->date('tanggal_per_batch');
            $table->date('tanggal_selesai_batch')->nullable();
            $table->integer('jumlah_peserta_tna')->default(0);
            $table->integer('jumlah_peserta_non_tna')->default(0);
            $table->decimal('biaya_investasi_perorang', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->string('status', 50)->default('Scheduled');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coe_events');
    }
};
