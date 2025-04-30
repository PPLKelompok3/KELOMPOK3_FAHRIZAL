<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Skill;
use App\Models\JobPost;

class LandingController extends Controller
{
    public function index(Request $request)
{
    $trendingKeywords = JobPost::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->inRandomOrder()
        ->limit(3)
        ->pluck('category');

    $recommendedSkills = [];

    if (Auth::check() && Auth::user()->role === 'applicant') {
        $recommendedSkills = Auth::user()->skills->pluck('name');
    }

    // ✅ Start building query dynamically
    $query = JobPost::with('company')
        ->where('status', 'Active');

        if ($request->filled('title')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%')
                  ->orWhereHas('company', function($q2) use ($request) {
                      $q2->where('company_name', 'like', '%' . $request->title . '%');
                  })
                  ->orWhere('skills', 'like', '%' . $request->title . '%'); // ✅ added this line
            });
        }
        

    if ($request->filled('location')) {
        $query->where('location', 'like', '%' . $request->location . '%');
    }

    if ($request->filled('employment_type')) {
        $query->where('employment_type', $request->employment_type);
    }

    $jobs = $query->latest()
        ->paginate(5)
        ->appends($request->query());

    $totalJobs = JobPost::where('status', 'Active')->count();

    if ($request->ajax()) {
        return view('partials.job-list', compact('jobs'))->render();
    }

    return view('index', compact('trendingKeywords', 'recommendedSkills', 'jobs', 'totalJobs'));
}



    public function jobListPartial()
{
    $jobs = JobPost::with('company')
        ->where('status', 'Active')
        ->latest()
        ->paginate(5)
        ->withPath('/ajax/jobs')
        ->appends(request()->query());
    return view('partials.job-list', compact('jobs'))->render(); // returns HTML string
}

}