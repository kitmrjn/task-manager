<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            // target_type: 'all', 'campaign', 'user'
            $table->string('target_type')->default('all');
            // target_id: null for 'all', campaign_id or user_id for others
            $table->unsignedBigInteger('target_id')->nullable();
            $table->timestamps();
        });

        Schema::create('memo_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->useCurrent();
            $table->unique(['memo_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memo_reads');
        Schema::dropIfExists('memos');
    }
};