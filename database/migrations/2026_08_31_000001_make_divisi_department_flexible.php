<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Jadikan id_divisi di tabel department menjadi NULLABLE
        DB::statement('ALTER TABLE department MODIFY id_divisi INT NULL');

        // 2. Tambahkan kolom id_divisi di tabel staff jika belum ada
        if (!Schema::hasColumn('staff', 'id_divisi')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->integer('id_divisi')->nullable()->after('tanggal_lahir');
            });
        }

        // 3. Jadikan id_department di tabel staff menjadi NULLABLE
        DB::statement('ALTER TABLE staff MODIFY id_department INT NULL');

        // 4. Sinkronkan id_divisi awal pada staff dari relasi department yang sudah ada
        DB::statement('UPDATE staff s JOIN department d ON s.id_department = d.id_department SET s.id_divisi = d.id_divisi WHERE s.id_department IS NOT NULL');
    }

    public function down()
    {
        if (Schema::hasColumn('staff', 'id_divisi')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('id_divisi');
            });
        }
    }
};
