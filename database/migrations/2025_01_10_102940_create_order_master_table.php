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
        Schema::create('order_master', function (Blueprint $table) {
            $table->id();
            $table->string('season_no');
            $table->string('order_trans')->unique();
            $table->string('buyer_no');
            $table->string('brand_no');
            $table->string('po_no');
            $table->string('style_no');
            $table->integer('qty_order')->default(0);
            $table->integer('qty_ocf')->default(0);
            $table->integer('qty_gmt')->default(0);
            $table->integer('qty_sbd')->default(0);
            $table->string('follow_up');
            $table->string('sketch_file');
            $table->string('wash_type');
            $table->string('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_master');
    }
};
