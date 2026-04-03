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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('role')->constrained()->nullOnDelete();
            $table->foreignId('team_leader_id')->nullable()->after('campaign_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropForeign(['team_leader_id']);
            $table->dropColumn(['campaign_id', 'team_leader_id']);
        });
    }
};