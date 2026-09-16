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
        Schema::create('ticket_sales', function (Blueprint $table) {
            $table->id();
            $table->uuid('fw_card_id')->unique(); // Bisa dipakai untuk penanda unik kartu
            $table->timestamp('timestamp')->useCurrent(); // Waktu transaksi
            $table->date('shift_date'); // Tanggal shift penjualan
            $table->string('operator_email');

            $table->foreignId('sales_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('nipp');

            $table->string('customer_name');
            $table->string('identity_number', 16);
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();

            $table->string('phone_number');

            $table->foreignId('trip_card_id')->constrained()->onDelete('cascade');

            $table->integer('quota'); 
            $table->string('serial_number')->unique(); 
            $table->string('edc_reference')->nullable(); 
            $table->integer('fw_price');
            
            $table->date('purchase_date'); 
            $table->integer('validity_days'); 
            $table->date('expired_date');

            $table->string('status_card')->default('Aktif');
            $table->enum('status', ['SUCCESS', 'PENDING']);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_sales');
    }
};
