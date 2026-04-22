<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'title')) {
                $table->string('title')->nullable()->after('id');
            }

            if (!Schema::hasColumn('reports', 'type')) {
                $table->string('type')->nullable()->after('title');
            }

            if (!Schema::hasColumn('reports', 'file_path')) {
                $table->string('file_path')->nullable()->after('recommendations');
            }

            if (!Schema::hasColumn('reports', 'generated_at')) {
                $table->timestamp('generated_at')->nullable()->after('file_path');
            }
        });

        $reports = DB::table('reports')->select('id', 'title', 'type', 'created_at', 'generated_at')->get();
        foreach ($reports as $report) {
            DB::table('reports')->where('id', $report->id)->update([
                'title' => $report->title ?: 'Report #' . $report->id,
                'type' => $report->type ?: 'Simulation Report',
                'generated_at' => $report->generated_at ?: $report->created_at,
            ]);
        }

        if (Schema::hasColumn('reports', 'simulation_id')) {
            try {
                Schema::table('reports', function (Blueprint $table) {
                    $table->dropForeign(['simulation_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if the foreign key was already removed.
            }

            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE reports MODIFY simulation_id BIGINT UNSIGNED NULL');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE reports ALTER COLUMN simulation_id DROP NOT NULL');
            }

            Schema::table('reports', function (Blueprint $table) {
                $table->foreign('simulation_id')->references('id')->on('simulations')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reports', 'simulation_id')) {
            try {
                Schema::table('reports', function (Blueprint $table) {
                    $table->dropForeign(['simulation_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if no foreign key exists.
            }

            Schema::table('reports', function (Blueprint $table) {
                $table->foreign('simulation_id')->references('id')->on('simulations')->cascadeOnDelete();
            });
        }

        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'generated_at')) {
                $table->dropColumn('generated_at');
            }

            if (Schema::hasColumn('reports', 'file_path')) {
                $table->dropColumn('file_path');
            }

            if (Schema::hasColumn('reports', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('reports', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};

