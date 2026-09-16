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
        Schema::dropIfExists('coe_training_events');
        Schema::create('coe_training_events', function (Blueprint $table) {
            $table->increments('id_training_event');
            $table->unsignedInteger('id_event')->nullable();
            $table->enum('tipe_penyelenggara', ['Internal', 'External'])->default('Internal');
            $table->string('nama_penyelenggara')->nullable();
            $table->string('trainer')->nullable();
            $table->string('manager_class')->nullable();
            $table->string('tipe_evaluasi')->nullable();
            $table->string('tipe_soal')->nullable();
            $table->string('ruangan')->nullable();
            $table->longText('peserta_tna')->nullable();
            $table->longText('peserta_non_tna')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_event')
                ->references('id_event')
                ->on('coe_events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coe_training_events');
    }
};
