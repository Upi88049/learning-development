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
            if (!Schema::hasColumn('training', 'kode_training')) {
                $table->string('kode_training', 50)->nullable()->after('id_training');
            }
            if (!Schema::hasColumn('training', 'scope_training')) {
                $table->enum('scope_training', ['In House', 'Out House'])->default('In House')->after('nama_training');
            }
        });

        // Pastikan seluruh data training yang sudah ada memiliki scope 'In House'
        $existing = \Illuminate\Support\Facades\DB::table('training')->orderBy('id_training', 'asc')->get();
        foreach ($existing as $t) {
            \Illuminate\Support\Facades\DB::table('training')
                ->where('id_training', $t->id_training)
                ->update([
                    'scope_training' => 'In House',
                    'kode_training' => $t->kode_training ?: ('TRN-' . str_pad($t->id_training, 3, '0', STR_PAD_LEFT)),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('training', 'kode_training')) {
                $cols[] = 'kode_training';
            }
            if (Schema::hasColumn('training', 'scope_training')) {
                $cols[] = 'scope_training';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
