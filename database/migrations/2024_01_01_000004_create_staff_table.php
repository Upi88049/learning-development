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
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->integer('id_staff', true);
                $table->integer('npk_staff');
                $table->string('nama_staff', 255);
                $table->integer('id_department')->nullable();
                $table->integer('id_jabatan_staff');
                $table->integer('id_immediate_manager')->nullable();
                $table->timestamps();

                $table->foreign('id_department', 'fk_staff_department')
                      ->references('id_department')
                      ->on('department');
                $table->foreign('id_jabatan_staff', 'fk_staff_level_jabatan')
                      ->references('id_level_jabatan')
                      ->on('level_jabatan');
                $table->foreign('id_immediate_manager', 'fk_staff_immediate_manager')
                      ->references('id_staff')
                      ->on('staff')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
