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
        Schema::create('fabrication', function (Blueprint $table) {
            $table->id();
            $table->string('order_trans')->unique();
            $table->string('fab_no')->unique();
            $table->string('fabmill_no');
            $table->text('fabrication');
            $table->text('po_fab');
            $table->text('etd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabrication');
    }
};
