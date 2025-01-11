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
        Schema::create('order_list', function (Blueprint $table) {
            $table->id();
            $table->string('order_trans');
            $table->string('order_list')->unique();
            $table->string('factory_no');
            $table->string('lot_no');
            $table->string('pobuyer_no');
            $table->integer('dcpo_qty');
            $table->date('ex_factory_date');
            $table->date('vsl_date');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_list');
    }
};
