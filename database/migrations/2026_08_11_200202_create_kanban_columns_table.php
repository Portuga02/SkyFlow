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

            // 1. O dono da coluna (Obrigatório)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 2. O time (Apenas cria o campo numérico, sem forçar a busca pela tabela teams agora)
            $table->unsignedBigInteger('team_id')->nullable();            
            
            $table->string('name'); 
            $table->string('slug'); 
            $table->string('color')->default('#f59e0b');
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