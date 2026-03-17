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
    Schema::table('tasks', function (Blueprint $table) {
        // We add a boolean column that defaults to 'false' (0)
        $table->boolean('is_completed')->default(false)->after('priority');
    });
}

public function down(): void
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('is_completed');
    });
}
};
