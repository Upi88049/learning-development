<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('dlc_accounts')) {
            Schema::create('dlc_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('password');
                $table->string('nama')->default('Admin DLC');
                $table->timestamps();
            });

            DB::table('dlc_accounts')->insert([
                'username' => 'admin_dlc',
                'password' => Hash::make('dlc2026'),
                'nama' => 'Admin DLC',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('dlc_accounts');
    }
};
