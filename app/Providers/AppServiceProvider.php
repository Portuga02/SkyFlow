<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Todo;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Força o carregamento de CSS/JS via HTTPS em produção
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Proteção: Impede o Laravel de buscar a tabela no banco durante o Build do Railway!
        if (! $this->app->runningInConsole()) {
            if (Schema::hasTable('todos')) {
                $totalTodos = Todo::count();
                View::share('totalTodos', $totalTodos);
            }
        }
    }
}