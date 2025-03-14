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
        Schema::create('proplan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('proplan_no')->unique();
            $table->string('order_trans');
            $table->string('order_list');
            $table->string('item');
            $table->string('category_no');
            $table->integer('percentage');
            $table->string('remark')->nullable();
            $table->string('status')->nullable();
            $table->string('void')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proplan_detail');
    }
};
