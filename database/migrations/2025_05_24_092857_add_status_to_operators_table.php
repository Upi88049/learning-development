<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->boolean('status')->default(true); // true: aktif, false: nonaktif
        });
    }

    public function down()
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
