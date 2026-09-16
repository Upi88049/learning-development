<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the person (Jaka, Kaban, Jaban)
            $table->enum('type', ['Gold', 'Silver']); // Type of card (Gold/Silver)
            $table->string('route'); // Route (e.g., Jakarta - Karawang)
            $table->integer('trips'); // Number of trips
            $table->integer('price'); // Price (Rp. 500.000, etc.)
            $table->integer('validity_days'); // Validity in days (60 for Gold, 30 for Silver)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_cards');
    }
}