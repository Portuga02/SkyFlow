<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Necessário para verificar se a tabela existe
use Illuminate\Support\Facades\View;   // Necessário para compartilhar a variável globalmente
use App\Models\Todo;                   // Necessário para contar as tarefas

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Proteção: O Laravel só vai tentar contar as tarefas se a tabela já existir no banco.
        // Isso impede que o comando 'php artisan migrate' quebre na inicialização!
        if (Schema::hasTable('todos')) {
            $totalTodos = Todo::count();
            View::share('totalTodos', $totalTodos);
        }
    }
}
