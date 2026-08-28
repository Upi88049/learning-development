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
        Schema::table('trip_cards', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('trip_cards', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
