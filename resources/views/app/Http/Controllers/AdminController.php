<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class AdminController extends Controller
{
    public function adminDashboard()
{
    $jobPostCount = JobPost::count();

    $jobPostStats = JobPost::whereNotNull('category')
    ->get()
    ->groupBy('category')
    ->map(function ($group, $category) {
        return [
            'category' => $category,
            'total' => $group->count(),
            'examples' => $group->pluck('title')->take(3)
        ];
    })->values();
    $categories = $jobPostStats->pluck('category');
    $categoryCounts = $jobPostStats->pluck('total');
    
     $applicationCount = JobApplication::count();

    // Get last 6 months in order with 0 fallback
    $monthlyApplications = JobApplication::select(
            DB::raw("DATE_FORMAT(created_at, '%b') as month"),
            DB::raw("COUNT(*) as count")
        )
        ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b')"))
        ->orderByRaw("MIN(created_at)")
        ->pluck('count', 'month');

    // Fill missing months with 0
    $months = collect(
        range(0, 5)
    )->map(function ($i) {
        return Carbon::now()->subMonths(5 - $i)->format('M'); // Jan, Feb, ...
    });

    $applicationSeries = $months->map(fn($month) => $monthlyApplications->get($month, 0));

    $adminUserStats = [
    [
        'title' => 'Total Users',
        'label' => 'All registered users',
        'count' => User::count(),
        'icon' => 'bx bx-user',
        'bg' => 'bg-label-primary'
    ],
    [
        'title' => 'Recruiters',
        'label' => 'Registered as Recruiter',
        'count' => User::where('role', 'recruiter')->count(),
        'icon' => 'bx bx-briefcase',
        'bg' => 'bg-label-info'
    ],
    [
        'title' => 'Applicants',
        'label' => 'Registered as Applicant',
        'count' => User::where('role', 'applicant')->count(),
        'icon' => 'bx bx-user-pin',
        'bg' => 'bg-label-success'
    ],
    [
        'title' => 'Recruiters Pending',
        'label' => 'Awaiting verification',
        'count' => User::where('role', 'recruiter')->where('is_verified', 'pending')->count(),
        'icon' => 'bx bx-time-five',
        'bg' => 'bg-label-warning'
    ],
    [
        'title' => 'Verified Recruiters',
        'label' => 'Approved Recruiters',
        'count' => User::where('role', 'recruiter')->where('is_verified', 'approved')->count(),
        'icon' => 'bx bx-check-shield',
        'bg' => 'bg-label-success'
    ],
    [
        'title' => 'Rejected Recruiters',
        'label' => 'Denied Recruiters requests',
        'count' => User::where('role', 'recruiter')->where('is_verified', 'rejected')->count(),
        'icon' => 'bx bx-block',
        'bg' => 'bg-label-danger'
    ],
    
];
        $recruiters = User::where('role', 'recruiter')
        ->with('company') 
        ->paginate(5); // 👈 Only 5 users per page

    return view('admin.dashboard', compact('jobPostStats', 'jobPostCount', 'categories', 'categoryCounts', 'applicationCount', 'applicationSeries', 'months', 'adminUserStats', 'recruiters'));

}
public function verifyRecruiter(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    $recruiter = User::findOrFail($id);

    if ($recruiter->role !== 'recruiter') {
        return back()->with('error', 'User is not a recruiter.');
    }

    $recruiter->is_verified = $request->status;
    $recruiter->save();

    return back()->with('success', 'Recruiter status updated.');
}

}