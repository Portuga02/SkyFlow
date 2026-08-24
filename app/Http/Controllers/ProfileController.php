<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $user->avatar_path = "data:{$mime};base64,{$base64}";
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $user = Auth::user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_path' => $path]);

        return back()->with('status', 'avatar-updated');
    }
    public function updateTheme(Request $request): RedirectResponse
    {
        $request->validate(['theme_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/']);

        Auth::user()->update(['theme_color' => $request->theme_color]);

        return back()->with('status', 'theme-updated');
    }
}
