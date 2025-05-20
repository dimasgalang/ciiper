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
        Schema::create('proplan_acc', function (Blueprint $table) {
            $table->id();
            $table->string('proplan_acc_no');
            $table->string('order_trans');
            $table->string('order_list');
            $table->string('category_no');
            $table->string('accesories_no');
            $table->date('item_date');
            $table->integer('qty');
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proplan_acc');
    }
};
