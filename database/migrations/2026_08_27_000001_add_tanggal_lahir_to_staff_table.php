<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('staff', 'tanggal_lahir')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->date('tanggal_lahir')->nullable()->after('nama_staff');
            });
        }

        $birthdates = [
            'Haniful Qayyim Apip' => '1978-04-02',
            'Rizal Adrian Saputra' => '1997-12-25',
            'Budi DLC' => '2002-12-25',
            'Abdul Rohim' => '1992-10-05',
            'Bagas Ardhi Pratama' => '1996-12-05',
            'Listia Ningtias' => '1999-04-05',
            'Lukman Hawari Pratama' => '1990-11-27',
            'Risse Noviane' => '1981-11-15',
            'Laja' => '2002-11-29',
        ];

        foreach ($birthdates as $name => $date) {
            DB::table('staff')
                ->where('nama_staff', 'LIKE', '%' . $name . '%')
                ->update(['tanggal_lahir' => $date]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('staff', 'tanggal_lahir')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('tanggal_lahir');
            });
        }
    }
};
