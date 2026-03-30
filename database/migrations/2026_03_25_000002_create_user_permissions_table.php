<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('can_view_calendar')->default(true);
            $table->boolean('can_view_analytics')->default(true);
            $table->boolean('can_view_team')->default(true);
            $table->boolean('can_view_reports')->default(true);
            $table->boolean('can_create_tasks')->default(true);
            $table->boolean('can_delete_tasks')->default(true);
            $table->boolean('can_edit_tasks')->default(true);
            $table->boolean('can_add_column')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};