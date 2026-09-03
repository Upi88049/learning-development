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
        Schema::dropIfExists('request_outhouse');
        Schema::create('request_outhouse', function (Blueprint $table) {
            $table->increments('id_request_outhouse');
            $table->string('no_request')->unique();
            $table->integer('id_staff');
            $table->integer('id_immediate_manager')->nullable();
            $table->string('judul_training');
            $table->text('deskripsi_training');
            $table->text('reason');
            $table->string('status')->default('Pending'); // Pending, Verified by DLC, Approve, Rejected With Reason
            $table->text('alasan_reject')->nullable();
            $table->timestamps();

            $table->foreign('id_staff')
                ->references('id_staff')
                ->on('staff')
                ->onDelete('cascade');

            $table->foreign('id_immediate_manager')
                ->references('id_staff')
                ->on('staff')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_outhouse');
    }
};
