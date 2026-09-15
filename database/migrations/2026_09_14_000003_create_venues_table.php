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
        if (!Schema::hasTable('venues')) {
            Schema::create('venues', function (Blueprint $table) {
                $table->increments('id_venue');
                $table->string('venue_code')->unique();
                $table->string('venue_name');
                $table->string('venue_type')->default('Internal'); // Internal / External
                $table->integer('capacity')->nullable();
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Seed initial venue data
            DB::table('venues')->insert([
                [
                    'venue_code' => 'RT-001',
                    'venue_name' => 'Ruang Training',
                    'venue_type' => 'Internal',
                    'capacity' => 30,
                    'location' => 'Gedung DLC Lt. 2',
                    'description' => 'Ruangan pelatihan utama dilengkapi proyektor & sound system.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'venue_code' => 'RT-002',
                    'venue_name' => 'DOJO',
                    'venue_type' => 'Internal',
                    'capacity' => 25,
                    'location' => 'Area Praktek Pabrik 1',
                    'description' => 'Area pelatihan simulasi keterampilan praktis dan standar kerja.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'venue_code' => 'RT-003',
                    'venue_name' => 'Auditorium',
                    'venue_type' => 'Internal',
                    'capacity' => 150,
                    'location' => 'Gedung Utama Lt. 3',
                    'description' => 'Aula besar untuk seminar, orientasi karyawan baru, dan pelatihan gabungan.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'venue_code' => 'RT-004',
                    'venue_name' => 'Rojo Safety',
                    'venue_type' => 'External',
                    'capacity' => 40,
                    'location' => 'Pusat Pelatihan K3 Eksternal',
                    'description' => 'Fasilitas eksternal bersertifikat untuk pelatihan keselamatan kerja & emergency response.',
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
        Schema::dropIfExists('venues');
    }
};
