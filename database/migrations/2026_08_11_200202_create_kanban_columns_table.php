<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanban_columns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();             
            $table->string('name'); 
            $table->string('slug'); 
            $table->string('color')->default('#f59e0b');
            $table->string('team_id');
            $table->integer('order')->default(0);
            $table->string('icon')->default('fa-layer-group');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanban_columns');
    }
};