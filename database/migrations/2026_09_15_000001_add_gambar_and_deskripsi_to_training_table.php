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
        Schema::table('training', function (Blueprint $table) {
            if (!Schema::hasColumn('training', 'gambar')) {
                $table->string('gambar', 255)->nullable()->after('gol_training');
            }
            if (!Schema::hasColumn('training', 'deskripsi_training')) {
                $table->text('deskripsi_training')->nullable()->after('gambar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training', function (Blueprint $table) {
            if (Schema::hasColumn('training', 'deskripsi_training')) {
                $table->dropColumn('deskripsi_training');
            }
            if (Schema::hasColumn('training', 'gambar')) {
                $table->dropColumn('gambar');
            }
        });
    }
};
