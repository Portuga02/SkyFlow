<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('team_id')->nullable();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('is_completed')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('kanban_column_id')->nullable();
            
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};