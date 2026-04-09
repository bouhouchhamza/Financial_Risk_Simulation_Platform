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
        Schema::table('fraud_rules', function (Blueprint $table) {
            $table->string('code')->unique()->after('name');
            $table->integer('score_weight')->default(10)->after('threshold_value');
            $table->string('decision_if_matched')->default('review')->after('score_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fraud_rules', function (Blueprint $table) {
            Schema::table('fraud_rules', function(Blueprint $table) {
                $table->dropColumn(['code', 'score_weight', 'decision_if_matched']);
            });
        });
    }
};
