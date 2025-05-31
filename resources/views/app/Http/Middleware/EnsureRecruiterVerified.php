<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRecruiterVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'recruiter' && $user->is_verified !== 'approved') {
            return redirect()->route('recruiter.unverified');
        }

        return $next($request);
    }
}