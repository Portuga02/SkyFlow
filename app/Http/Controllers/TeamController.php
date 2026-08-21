<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        // Busca todos da mesma equipe
        $members = User::where('team_id', Auth::user()->team_id)
            ->orderBy('role') // Admins primeiro
            ->orderBy('name')
            ->get();

        return view('auth.team.index', compact('members'));
    }

    public function store(Request $request)
    {
        // Trava: Só Admin pode convidar!
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem convidar novos membros.');
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:admin,member',
        ]);

        // Gera uma senha aleatória de 8 caracteres
        $password = Str::random(8);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($password),
            'team_id'  => Auth::user()->team_id,
            'role'     => $validated['role'],
        ]);

        // Aqui você colocaria a rotina de envio de e-mail:
        // Mail::to($user->email)->send(new InviteMail($user, $password));

        // Para testes, vamos mostrar a senha gerada na notificação da tela:
        return back()->with('alert-success', "{$user->name} adicionado! A senha de acesso gerada foi: {$password}");
    }
}