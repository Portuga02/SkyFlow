<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        // Lista todos os usuários que pertencem à sua equipe
        $members = User::where('team_id', Auth::user()->team_id)->get();

        return view('auth.team.index', compact('members'));
    }
}
