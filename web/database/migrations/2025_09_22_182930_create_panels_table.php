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
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->string('panel_id', 30)->unique();
            $table->string('unit_id', 30);
            $table->foreign('unit_id')->references('unit_id')->on('units')->cascadeOnDelete();
            $table->float('current')->default(0);
            $table->float('voltage')->default(0);
            $table->float('power')->default(0);
            $table->boolean('rain_status')->default(false);
            $table->boolean('wiper_status')->default(false);
            $table->timestamp('last_cleaning')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panels');
    }
};
