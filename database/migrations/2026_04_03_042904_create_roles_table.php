<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // e.g., 'super_admin'
            $table->boolean('is_system')->default(false); // Prevents deleting core roles
            $table->timestamps();
        });

        // Insert the core roles automatically
        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'slug' => 'manager', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Team Member', 'slug' => 'team_member', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};