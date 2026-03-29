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
        Schema::create('simulations',function(Blueprint $table){
            $table->id();
            $table->foreignId('startup_id')->constrained()->cascadeOnDelete();
            $table->enum('duration',['6_month','12_month']);
            $table->decimal('total_revenue',10,2);
            $table->decimal('total_expenses',10,2);
            $table->decimal('final_cashflow',10,2);
            $table->enum('risk_level',['low','medium','high']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
