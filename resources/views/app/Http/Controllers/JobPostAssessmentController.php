<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobPostAssessmentController extends Controller
{
    public function show(JobPost $job, JobApplication $application)
{
    $assessment = $job->assessment;

    if (!$assessment || $application->user_id !== auth::id()) {
        abort(403);
    }

    // Use the passed $application directly
    $appliedAt = $application->created_at;
    $dueDays = $assessment->due_in_days ?? 0;
    $dueDate = $appliedAt->copy()->addDays($dueDays);

    $remainingDays = now()->lt($dueDate)
    ? ceil(now()->floatDiffInDays($dueDate))
    : 0;


    return view('jobsmanagement.assessments.' . $assessment->type, [
        'job' => $job,
        'application' => $application,
        'assessment' => $assessment,
        'dueDate' => $dueDate,
        'remainingDays' => $remainingDays,
    ]);
}

}