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
            $table->string('plan_no');
            $table->string('order_trans');
            $table->string('order_list');
            $table->string('has_sample');
            $table->date('sample_date')->nullable();
            $table->string('has_mi');
            $table->date('mi_date')->nullable();
            $table->string('has_fab_cart');
            $table->date('fab_date')->nullable();
            $table->string('has_acc_cart');
            $table->date('acc_date')->nullable();
            $table->date('bordir_approve')->nullable();
            $table->date('pattern_date')->nullable();
            $table->date('sampletest_date')->nullable();
            $table->date('reqmarker_date')->nullable();
            $table->date('marker_date')->nullable();
            $table->date('pilotrun_date')->nullable();
            $table->date('ppm_date')->nullable();
            $table->date('startcut_date')->nullable();
            $table->date('finishcut_date')->nullable();
            $table->date('startsew_date')->nullable();
            $table->date('finishsew_date')->nullable();
            $table->date('finishpack_date')->nullable();
            $table->string('remark')->nullable();
            $table->string('void')->nullable();
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
