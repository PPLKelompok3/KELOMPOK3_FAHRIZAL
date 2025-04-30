<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    $user = Auth::user();

    // 🛠️ Custom logic for recruiter if needed
    if ($user->role === 'recruiter') {
        if (!$user->company) {
            return redirect()->route('recruiter.profile'); // Still force recruiter to complete profile
        }
    }

    // ✅ Force all to homepage after login
    return redirect()->intended('/');
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}