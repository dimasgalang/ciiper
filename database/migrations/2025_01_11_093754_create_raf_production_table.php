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
        Schema::create('raf_production', function (Blueprint $table) {
            $table->id();
            $table->string('raf_no')->unique();
            $table->string('order_list');
            $table->date('raf_date');
            $table->integer('raf_qty');
            $table->string('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raf_production');
    }
};
