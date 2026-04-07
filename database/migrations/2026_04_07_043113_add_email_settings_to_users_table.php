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
            $table->string('email_imap_host')->nullable();
            $table->integer('email_imap_port')->default(993);
            $table->string('email_smtp_host')->nullable();
            $table->integer('email_smtp_port')->default(465);
            $table->string('email_username')->nullable();
            $table->text('email_password')->nullable(); // Text column required for long encrypted strings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_imap_host',
                'email_imap_port',
                'email_smtp_host',
                'email_smtp_port',
                'email_username',
                'email_password'
            ]);
        });
    }
};