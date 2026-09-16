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
        if (!Schema::hasTable('department')) {
            Schema::create('department', function (Blueprint $table) {
                $table->integer('id_department', true);
                $table->integer('id_divisi')->nullable();
                $table->string('nama_department', 255);
                $table->timestamps();

                $table->foreign('id_divisi', 'fk_department_divisi')
                      ->references('id_divisi')
                      ->on('divisi')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
};
