<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    public function up()
{
    Schema::create('calendar_events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->date('date');
        $table->string('time')->nullable();
        $table->string('type')->default('meeting');
        $table->string('color')->default('blue');
        $table->text('description')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created it
        $table->timestamps();
    });
}
}
