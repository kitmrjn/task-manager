<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->time('shift_start')->nullable()->after('description');
            $table->time('shift_end')->nullable()->after('shift_start');
            $table->string('timezone', 100)->nullable()->after('shift_end');
            $table->json('operating_days')->nullable()->after('timezone'); // ["Mon","Tue","Wed","Thu","Fri"]
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['shift_start', 'shift_end', 'timezone', 'operating_days']);
        });
    }
};