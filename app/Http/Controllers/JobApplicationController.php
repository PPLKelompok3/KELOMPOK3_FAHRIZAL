<?php

namespace App\Http\Controllers;
use App\Models\JobApplication;
use App\Models\Notification;


use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function updateStatus(Request $request, $id)
{
    $application = JobApplication::findOrFail($id);

    $validated = $request->validate([
        'status' => 'required|in:Pending,Processed,Interview,Accepted,Rejected',
    ]);

    $application->status = $validated['status'];

    // Optional: Reset interview_date if status is NOT Interview
    if ($application->status !== 'Interview') {
        $application->interview_date = null;
    }

    $application->save();
    // After $application->save();
    Notification::create([
        'user_id' => $application->user_id,
        'type' => 'Application Status',
        'content' => 'Your application for "' . $application->job->title . '" is now "' . $application->status . '".',
        'is_read' => false,
        'company_logo_url' => $application->job->company->logo_url ?? null
    ]);


    return redirect()->back()->with('success', 'Application status updated successfully.');
}

public function show($id)
{
    $application = JobApplication::with(['user', 'job.company'])->findOrFail($id);

    return view('recruiter.applicationdetails', compact('application'));
}


}