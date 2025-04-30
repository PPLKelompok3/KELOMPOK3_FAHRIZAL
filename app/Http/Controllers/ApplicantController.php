<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function appliedJobs()
{
    $user = Auth::user();
    $applications = JobApplication::with(['job.company'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    return view('applicant.dashboard', compact('applications'));
}
}