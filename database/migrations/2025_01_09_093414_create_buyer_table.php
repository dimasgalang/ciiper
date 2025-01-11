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
        Schema::create('buyer', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_no')->unique();
            $table->string('buyer_name');
            $table->string('buyer_address')->nullable();
            $table->string('buyer_contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer');
    }
};
