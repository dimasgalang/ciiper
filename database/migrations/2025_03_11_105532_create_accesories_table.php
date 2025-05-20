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
        Schema::create('accesories', function (Blueprint $table) {
            $table->id();
            $table->string('accesories_no');
            $table->string('category_no');
            $table->string('accesories_name');
            $table->string('accesories_unit');
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesories');
    }
};
