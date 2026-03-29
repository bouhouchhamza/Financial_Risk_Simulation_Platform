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
        Schema::create('startups', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique('user_id');
            $table->string('name');
            $table->string('activity_type');
            $table->decimal('initial_budget',10,2);
            $table->decimal('monthly_revenue',10,2);
            $table->integer('employees_count');
            $table->decimal('monthly_expenses',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startups');
    }
};
