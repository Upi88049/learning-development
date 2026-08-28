<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('email_configs')) {
            Schema::create('email_configs', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            DB::table('email_configs')->insert([
                [
                    'key' => 'recipients',
                    'value' => "manager1@dharma.com\nmanager2@dharma.com",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'subject',
                    'value' => 'Pemberitahuan Periode Training Need Analysis (TNA)',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'body',
                    'value' => "Yth. Immediate Manager,\n\nPeriode pengisian dan peninjauan Training Need Analysis (TNA) telah dibuka.\nSilakan akses dashboard Anda melalui link berikut untuk melihat daftar staff dan status training staff Anda:\n\nhttp://localhost/learningDevelopment/public/users\n\nTerima kasih,\nLearning & Development DLC",
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('email_configs');
    }
};
