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
        Schema::create('redeem_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redeem_quota_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->timestamp('redeemed_at')->nullable(); // ✅ ditambahkan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeem_logs');
    }
};
