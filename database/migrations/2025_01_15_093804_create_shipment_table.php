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
        Schema::create('shipment', function (Blueprint $table) {
            $table->id();
            $table->string('ship_no')->unique();
            $table->string('order_list');
            $table->string('market_no');
            $table->string('size_no');
            $table->integer('ship_qty');
            $table->integer('carton_qty');
            $table->date('ship_date');
            $table->string('remark')->nullable();
            $table->string('void')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment');
    }
};
