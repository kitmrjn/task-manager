<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_log_id')->constrained('time_logs')->cascadeOnDelete();
            $table->string('break_type'); // 'first', 'lunch', 'last'
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_logs');
    }
};