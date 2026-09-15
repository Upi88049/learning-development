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
        if (!Schema::hasTable('instructors')) {
            Schema::create('instructors', function (Blueprint $table) {
                $table->increments('id_instructor');
                $table->string('instructor_code')->unique();
                $table->string('instructor_name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('specialization')->nullable();
                $table->timestamps();
            });

            // Seed initial instructor data
            DB::table('instructors')->insert([
                [
                    'instructor_code' => '11230231',
                    'instructor_name' => 'Marvel Moraro Alfonso Adijayato Nados',
                    'email' => null,
                    'phone' => null,
                    'specialization' => 'Technical & Engineering',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_code' => '12061556',
                    'instructor_name' => 'Firmansyah Heri Wibowo',
                    'email' => null,
                    'phone' => null,
                    'specialization' => 'Quality & Operations',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_code' => '13991008',
                    'instructor_name' => 'Ahmad Saripudin',
                    'email' => null,
                    'phone' => null,
                    'specialization' => 'Safety & DOJO Trainer',
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
        Schema::dropIfExists('instructors');
    }
};
