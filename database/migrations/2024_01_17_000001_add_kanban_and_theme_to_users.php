<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('name');
            $table->string('theme_color')->default('#0c8fe6')->after('avatar_path'); // Cor tema
            $table->string('view_mode')->default('list')->after('theme_color'); // list ou kanban
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'theme_color', 'view_mode']);
        });
    }
};
