<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class RecruiterDashboardController extends Controller
{
    public function recruiterDashboard()
{
    $companyId = Auth::user()->company_id;

    // Small Dashboard Cards
    $totalApplicants = JobApplication::whereHas('job', function ($query) use ($companyId) {
        $query->where('company_id', $companyId);
    })->count();

    $acceptedApplicants = JobApplication::whereHas('job', function ($query) use ($companyId) {
        $query->where('company_id', $companyId);
    })->where('status', 'Accepted')->count();

    $rejectedApplicants = JobApplication::whereHas('job', function ($query) use ($companyId) {
        $query->where('company_id', $companyId);
    })->where('status', 'Rejected')->count();

    $totalJobs = JobPost::where('company_id', $companyId)->count();

    // Job Posts for Dropdown
    $jobPosts = JobPost::where('company_id', $companyId)
        ->where('status', 'Active')
        ->pluck('title', 'id'); // ['id' => 'title']

    return view('recruiter.dashboard', compact(
        'totalApplicants', 
        'acceptedApplicants', 
        'rejectedApplicants', 
        'totalJobs', 
        'jobPosts'
    ));
}
public function getJobApplicantsGraph(Request $request)
{
    $jobId = $request->job_id;

    // Group applicants by month
    $monthlyApplicants = JobApplication::where('job_id', $jobId)
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month'); // [month => total]

    return response()->json($monthlyApplicants);
}

}