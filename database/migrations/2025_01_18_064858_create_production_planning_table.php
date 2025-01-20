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
        Schema::create('production_planning', function (Blueprint $table) {
            $table->id();
            $table->string('order_trans');
            $table->string('order_list');
            $table->string('has_sample');
            $table->string('has_mi');
            $table->string('has_cart');
            $table->date('fab_date');
            $table->date('acc_date');
            $table->date('bordir_approve');
            $table->date('pattern_date');
            $table->date('sampletest_date');
            $table->date('marker_date');
            $table->date('pilotrun_date');
            $table->date('ppm_date');
            $table->date('startcut_date');
            $table->date('finishcut_date');
            $table->date('startsew_date');
            $table->date('finishsew_date');
            $table->date('finishpack_date');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_planning');
    }
};
