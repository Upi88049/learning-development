<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('redeem_quotas', function (Blueprint $table) {
            $table->foreignId('ticket_sale_id')->constrained()->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('redeem_quotas', function (Blueprint $table) {
            $table->dropForeign(['ticket_sale_id']);
            $table->dropColumn('ticket_sale_id');
        });
    }
    
};
