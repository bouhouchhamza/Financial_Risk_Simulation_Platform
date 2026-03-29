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
        Schema::create('transactions',function(Blueprint $table){
            $table->id();
            $table->foreignId('startup_id')->constrained()->cascadeOnDelete();
            $table->enum('type',['sale','purchase','transfer']);
            $table->decimal('amount',10,2);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
