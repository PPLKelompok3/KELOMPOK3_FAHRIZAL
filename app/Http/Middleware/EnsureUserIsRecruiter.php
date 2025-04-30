<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class EnsureUserIsRecruiter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()&& Auth::user()->role === 'recruiter') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized. Recruiter access only.');
    }
}