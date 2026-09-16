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
        if (!Schema::hasTable('staff_training')) {
            Schema::create('staff_training', function (Blueprint $table) {
                $table->integer('id_staff_training', true);
                $table->integer('id_staff');
                $table->integer('id_training');
                $table->integer('id_status');

                $table->foreign('id_staff', 'fk_staff')
                      ->references('id_staff')
                      ->on('staff');
                $table->foreign('id_training', 'fk_training')
                      ->references('id_training')
                      ->on('training');
                $table->foreign('id_status', 'fk_status_training')
                      ->references('id_status')
                      ->on('status_training');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_training');
    }
};
