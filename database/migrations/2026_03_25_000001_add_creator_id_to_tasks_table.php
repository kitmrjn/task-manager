<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add creator_id if it doesn't exist
            if (!Schema::hasColumn('tasks', 'creator_id')) {
                $table->foreignId('creator_id')
                      ->nullable()
                      ->after('assigned_to')
                      ->constrained('users')
                      ->nullOnDelete();
            }

            // Add start_date if it doesn't exist (you use it in blade but it's not in migration)
            if (!Schema::hasColumn('tasks', 'start_date')) {
                $table->date('start_date')->nullable()->after('due_date');
            }

            // Add is_completed if it doesn't exist
            if (!Schema::hasColumn('tasks', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('creator_id');
            $table->dropColumn(['start_date', 'is_completed']);
        });
    }
};