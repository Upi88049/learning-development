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
    public function up(): void
    {
        if (!Schema::hasTable('providers')) {
            Schema::create('providers', function (Blueprint $table) {
                $table->increments('id_provider');
                $table->string('provider_code')->unique();
                $table->string('provider_name');
                $table->string('provider_type')->default('External'); // External, Internal, Konsultan, Lembaga Sertifikasi, etc.
                $table->string('pic')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });

            // Seed initial provider data
            DB::table('providers')->insert([
                [
                    'provider_code' => 'PRV-001',
                    'provider_name' => 'PT Brainmatics Cipta Informatika',
                    'provider_type' => 'External',
                    'pic' => 'Romi Satria Wahono',
                    'phone' => '021-83793383',
                    'email' => 'info@brainmatics.com',
                    'address' => 'Menara Bidakara 1 Lt. 2, Jl. Gatot Subroto Kav. 71-73, Jakarta Selatan',
                    'note' => 'Spesialis pelatihan IT, Programming, dan Software Architecture.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'provider_code' => 'PRV-002',
                    'provider_name' => 'Internal Dharma Learning Center (DLC)',
                    'provider_type' => 'Internal',
                    'pic' => 'Herwin Gultom',
                    'phone' => '021-89830001',
                    'email' => 'dlc@dharmap.com',
                    'address' => 'Kawasan Industri GIIC Cikarang, Bekasi',
                    'note' => 'Penyelenggara pelatihan internal dan orientasi budaya Dharma Group.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'provider_code' => 'PRV-003',
                    'provider_name' => 'Dunamis Organization Services',
                    'provider_type' => 'Konsultan',
                    'pic' => 'Maya Arvini',
                    'phone' => '021-57900888',
                    'email' => 'info@dunamis.co.id',
                    'address' => 'Sampoerna Strategic Square, South Tower Lt. 28, Jakarta Pusat',
                    'note' => 'Mitra pelatihan Leadership, The 7 Habits, dan Corporate Culture.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'provider_code' => 'PRV-004',
                    'provider_name' => 'TÜV Rheinland Indonesia',
                    'provider_type' => 'Lembaga Sertifikasi',
                    'pic' => 'Dian Prasetyo',
                    'phone' => '021-39704599',
                    'email' => 'cs@idn.tuv.com',
                    'address' => 'Menara Dea Tower 1 Lt. 8, Mega Kuningan, Jakarta Selatan',
                    'note' => 'Pelatihan dan sertifikasi sistem mutu ISO 9001, IATF 16949, dan K3.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
