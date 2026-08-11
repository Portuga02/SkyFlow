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
            // Atrela a coluna ao usuário logado (cada um tem seu próprio quadro!)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            
            $table->string('name'); // O nome que aparece na tela (Ex: Em Revisão)
            $table->string('slug'); // O identificador interno (Ex: em-revisao) - vai bater com o 'status' do Todo
            $table->string('color')->default('#f59e0b'); // A cor que você escolheu no modal
            $table->integer('order')->default(0); // Para poder reordenar as colunas no futuro
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanban_columns');
    }
};