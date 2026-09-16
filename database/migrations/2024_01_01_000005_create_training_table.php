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
        if (!Schema::hasTable('training')) {
            Schema::create('training', function (Blueprint $table) {
                $table->integer('id_training', true);
                $table->string('jenis_training', 255);
                $table->string('nama_training', 255);
                $table->string('mandatory_training', 255)->nullable();
                $table->string('gol_training', 255)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training');
    }
};
