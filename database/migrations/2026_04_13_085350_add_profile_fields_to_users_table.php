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
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->string('address')->nullable()->after('city');
            $table->string('country')->default('Philippines')->after('address');
            $table->string('photo')->nullable()->after('country');
            $table->string('sss_number')->nullable()->after('photo');
            $table->string('philhealth_number')->nullable()->after('sss_number');
            $table->string('tin_number')->nullable()->after('philhealth_number');
            $table->string('pag_ibig_number')->nullable()->after('tin_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'city', 'address', 'country', 'photo',
                'sss_number', 'philhealth_number', 'tin_number', 'pag_ibig_number',
            ]);
        });
    }
};
