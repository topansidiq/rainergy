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
        Schema::create('panel_readings', function (Blueprint $table) {
            $table->id();
            $table->string('panel_id');
            $table->string('data_id');
            $table->float('dust')->default(0);
            $table->float('current')->default(0);
            $table->float('voltage')->default(0);
            $table->float('power')->default(0);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_readings');
    }
};
