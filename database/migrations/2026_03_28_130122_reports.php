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
        Schema::create('reports',function(Blueprint $table){
            $table->id();
            $table->foreignId('startup_id')->constrained()->cascadeOnDelete();
            $table->foreignId('simulation_id')->constrained()->cascadeOnDelete();
            $table->string('summary');
            $table->string('risk_level');
            $table->text('recommendations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
