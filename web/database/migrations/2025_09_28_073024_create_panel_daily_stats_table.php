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
        Schema::create('panel_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->string('panel_id', 30);
            $table->foreign('panel_id')->references('panel_id')->on('panels')->cascadeOnDelete();
            $table->float('avg_voltage')->default(0);
            $table->float('avg_current')->default(0);
            $table->float('avg_power')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_daily_stats');
    }
};
