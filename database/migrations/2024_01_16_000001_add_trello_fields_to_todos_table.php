<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->string('priority')->default('medium')->after('is_completed'); // low | medium | high
            $table->date('due_date')->nullable()->after('priority');

            $table->foreignId('assigned_to')
                ->nullable()
                ->after('due_date')
                ->constrained('users')
                ->nullOnDelete();

            // Colunas JSON: forma rápida de suportar recursos "estilo Trello"
            // sem precisar criar tabelas relacionais extras agora.
            $table->json('labels')->nullable()->after('assigned_to');       // [{ "name": "Urgente", "color": "#ef4444" }]
            $table->json('checklist')->nullable()->after('labels');         // [{ "text": "Passo 1", "done": false }]
            $table->json('comments')->nullable()->after('checklist');       // [{ "user": "João", "body": "...", "at": "..." }]
            $table->json('attachments')->nullable()->after('comments');     // [{ "name": "arquivo.pdf", "path": "..." }]
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn(['priority', 'due_date', 'labels', 'checklist', 'comments', 'attachments']);
        });
    }
};
