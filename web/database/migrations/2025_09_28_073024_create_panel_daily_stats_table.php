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
            $table->foreignId('panel_id')->constrained('panels')->cascadeOnDelete();
            $table->date('date');        // tanggal
            $table->tinyInteger('hour'); // jam 0-23
            $table->float('avg_voltage')->default(0);
            $table->float('avg_current')->default(0);
            $table->float('avg_power')->default(0);
            $table->float('avg_dust')->default(0);
            $table->timestamps();
            $table->unique(['panel_id', 'date', 'hour']); // biar tidak duplikat
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
