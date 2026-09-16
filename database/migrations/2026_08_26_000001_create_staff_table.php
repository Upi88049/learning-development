<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Base "staff" table. Kolom tambahan (tanggal_lahir, email, id_divisi)
     * ditambahkan oleh migration lain yang berjalan setelah ini.
     */
    public function up(): void
    {
        if (Schema::hasTable('staff')) {
            return;
        }

        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id_staff');
            $table->string('npk_staff', 50)->nullable();
            $table->string('nama_staff', 255);
            $table->integer('id_department')->nullable();
            $table->integer('id_jabatan_staff')->nullable();
            $table->integer('id_immediate_manager')->nullable();
            $table->timestamps();

            $table->index('npk_staff');
            $table->index('id_department');
            $table->index('id_jabatan_staff');
            $table->index('id_immediate_manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
