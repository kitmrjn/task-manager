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
        Schema::create('user_valid_ids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_type'); // sss_card, philhealth_card, tin_card, pagibig_card, passport, drivers_license
            $table->string('file_path');
            $table->string('original_filename');
            $table->timestamps();

            $table->unique(['user_id', 'id_type']); // one of each type per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_valid_ids');
    }
};
