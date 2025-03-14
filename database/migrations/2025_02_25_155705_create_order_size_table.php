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
        Schema::create('order_size', function (Blueprint $table) {
            $table->id();
            $table->string('order_size_no')->unique();
            $table->string('order_list');
            $table->string('order_trans');
            $table->string('size_no');
            $table->integer('qty');
            $table->integer('void')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_size');
    }
};
