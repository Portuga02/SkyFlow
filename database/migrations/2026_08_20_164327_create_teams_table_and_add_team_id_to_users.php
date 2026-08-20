<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cria a tabela de equipes
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Advocacia João", "Devs Topados"
            $table->timestamps();
        });

        // 2. Adiciona a coluna team_id na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::dropIfExists('teams');
    }
};
