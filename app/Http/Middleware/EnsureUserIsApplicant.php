<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApplicant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()&& Auth::user()->role === 'applicant') {
            return $next($request);
        }

        // Optionally redirect or abort
        return redirect('/')->with('error', 'Unauthorized access.');
        // or: abort(403);
    }
}