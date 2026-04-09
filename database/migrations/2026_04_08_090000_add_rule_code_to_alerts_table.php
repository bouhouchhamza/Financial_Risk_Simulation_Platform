<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->string('rule_code')->nullable()->after('type');
            $table->index(['startup_id', 'rule_code'], 'alerts_startup_rule_index');
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropIndex('alerts_startup_rule_index');
            $table->dropColumn('rule_code');
        });
    }
};
