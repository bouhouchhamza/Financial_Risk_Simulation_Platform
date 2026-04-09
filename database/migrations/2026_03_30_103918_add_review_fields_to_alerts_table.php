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
        Schema::table('alerts', function (Blueprint $table) {
            $table->string('review_status')->default('pending_review')->after('status');
            $table->text('review_note')->nullable()->after('review_status');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->after('review_note');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['review_status', 'review_note', 'reviewed_at']);
        });
    }
};
