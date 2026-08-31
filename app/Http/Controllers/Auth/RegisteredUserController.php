<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Team;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $team = Team::create([
            'name' => 'Equipe de ' . $request->name,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'team_id' => $team->id,
        ]);

        // Criação das categorias iniciais padrão
        $defaultCategories = [
            ['name' => 'Trabalho',   'icon' => 'fa-solid fa-briefcase',   'color' => '#0284c7'],
            ['name' => 'Pessoal',    'icon' => 'fa-solid fa-house',       'color' => '#10b981'],
            ['name' => 'Estudos',    'icon' => 'fa-solid fa-book',        'color' => '#8b5cf6'],
            ['name' => 'Financeiro', 'icon' => 'fa-solid fa-dollar-sign', 'color' => '#f59e0b'],
        ];

        foreach ($defaultCategories as $cat) {
            Category::create(array_merge($cat, [
                'user_id' => $user->id,
                // 'team_id' => $team->id, // Descomente caso sua tabela categories utilize team_id
            ]));
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}