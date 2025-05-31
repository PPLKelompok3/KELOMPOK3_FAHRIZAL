<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\JobApplication;
use App\Models\Notification;
use App\Models\AssessmentSubmission;
class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function create()
{
    return view('jobsmanagement.create');
}


public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'employment_type' => 'required|string',
        'location' => 'nullable|string',
        'salary_min' => 'nullable|integer',
        'salary_max' => 'nullable|integer|gte:salary_min',
        'experience_level' => 'nullable|string',
        'skills' => 'nullable|string',
        'category' => 'nullable|string',

        // Assessment type and due date (optional)
        'assessment_type' => 'nullable|in:essay,file_upload',
        'assessment_due_in_days' => 'nullable|integer|min:1',
        'essay_questions' => 'nullable|string', // TinyMCE content
        'file_instruction' => 'nullable|string',
        'file_guide' => 'nullable|file|mimes:pdf,docx',
    ]);

    $data['company_id'] = Auth::user()->company_id;

    // Skill parsing
    $skills = collect(json_decode($request->skills, true))
        ->pluck('value')
        ->filter()
        ->map(fn($skill) => ucfirst(strtolower(trim($skill))))
        ->unique()
        ->values();

    $data['skills'] = $skills->implode(',');

    $categoryRaw = $request->input('category');
    $decoded = json_decode($categoryRaw, true);
    $data['category'] = is_array($decoded) && isset($decoded[0]['value'])
        ? $decoded[0]['value']
        : null;

    // ✅ Save job post
    $job = JobPost::create($data);

    // ✅ Save new skills
    foreach ($skills as $skillName) {
        \App\Models\Skill::firstOrCreate(['name' => $skillName]);
    }

    // ✅ Store optional assessment
if ($request->filled('assessment_type')) {
    $assessmentData = [
        'job_post_id' => $job->id,
        'type' => $request->input('assessment_type'),
        'due_in_days' => $request->input('assessment_due_in_days'),
        'instruction' => $request->input('assessment_instruction') ?? '',
        'attachment' => null, // ← update here
    ];

    if ($request->input('assessment_type') === 'essay') {
        $assessmentData['instruction'] = $request->input('essay_questions');
    } elseif ($request->input('assessment_type') === 'file_upload') {
        $assessmentData['instruction'] = $request->input('file_instruction');
        if ($request->hasFile('file_guide')) {
            $assessmentData['attachment'] = $request->file('file_guide')->store('assessment_guides', 'public');
        }
    }

    \App\Models\JobPostAssessment::create($assessmentData);
}
    

    return redirect()->route('jobs.index')->with('success', 'Job posted!');
}



public function edit(JobPost $job)
{
    $assessment = $job->assessment; 
    return view('jobsmanagement.edit', compact('job', 'assessment'));
}


public function update(Request $request, JobPost $job)
{
    $data = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'employment_type' => 'required|string',
        'location' => 'nullable|string',
        'salary_min' => 'nullable|integer',
        'salary_max' => 'nullable|integer|gte:salary_min',
        'experience_level' => 'nullable|string',
        'skills' => 'nullable|string',
        'category' => 'nullable|string',

        // Optional assessment fields
        'assessment_type' => 'nullable|in:essay,file_upload',
        'assessment_due_in_days' => 'nullable|integer|min:1',
        'essay_questions' => 'nullable|string', // TinyMCE content
        'file_instruction' => 'nullable|string',
        'file_guide' => 'nullable|file|mimes:pdf,docx',
    ]);

    // Handle skills
    $skills = collect(json_decode($request->skills, true))
        ->pluck('value')
        ->filter()
        ->map(fn($skill) => ucfirst(strtolower(trim($skill))))
        ->unique()
        ->values();
    $data['skills'] = $skills->implode(',');

    foreach ($skills as $skillName) {
        \App\Models\Skill::firstOrCreate(['name' => $skillName]);
    }

    // Handle category
    $categoryRaw = $request->input('category');
    $decoded = json_decode($categoryRaw, true);
    $data['category'] = is_array($decoded) && isset($decoded[0]['value'])
        ? $decoded[0]['value']
        : null;

    // ✅ Update job post
    $job->update($data);

    // ✅ Update or delete assessment
    if ($request->filled('assessment_type')) {
    $assessmentData = [
        'job_post_id' => $job->id,
        'type' => $request->input('assessment_type'),
        'due_in_days' => $request->input('assessment_due_in_days'),
        'instruction' => $request->input('assessment_instruction') ?? '',
        'attachment' => null, // ← update here
    ];

       if ($request->input('assessment_type') === 'essay') {
        $assessmentData['instruction'] = $request->input('essay_questions');
    } elseif ($request->input('assessment_type') === 'file_upload') {
        $assessmentData['instruction'] = $request->input('file_instruction');
        if ($request->hasFile('file_guide')) {
            $assessmentData['attachment'] = $request->file('file_guide')->store('assessment_guides', 'public');
        }
    }

        // Create or update assessment record
        $job->assessment()->updateOrCreate([], $assessmentData);

    } else {
        // If assessment_type is empty but one exists → delete it
        if ($job->assessment) {
            $job->assessment->delete();
        }
    }

    return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
}



public function destroy(JobPost $job)
{
    $job->delete();
    return redirect()->route('jobs.index')->with('success', 'Job deleted!');
}
public function toggleStatus(JobPost $job)
{
    $job->status = $job->status === 'Active' ? 'Not Active' : 'Active';
    $job->save();

    return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
}
public function show($id, $slug = null)
{
    $job = JobPost::with('company')->findOrFail($id);

    $isApplied = false;

    if (Auth::check() && Auth::user()->role === 'applicant') {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // ➔ Help the editor know this is a User
        $isApplied = $user->applications()
            ->where('job_id', $job->id)
            ->exists();
    }

    return view('jobsmanagement.Details', compact('job', 'isApplied'));
}


public function relatedJobs(Request $request, $id)
{
    $currentJob = JobPost::findOrFail($id);

    $relatedJobs = JobPost::with('company')
        ->where('status', 'Active')
        ->where('id', '!=', $currentJob->id)
        ->where('category', $currentJob->category)
        ->latest()
        ->paginate(4);

    // TEMPORARY: allow normal browser view for testing
    return view('partials.related-jobs', compact('relatedJobs'));

    // FINAL VERSION:
    // if ($request->ajax()) {
    //     return view('partials.related-jobs', compact('relatedJobs'));
    // }
    //
    // abort(404);
}
// public function apply(Request $request, $id)
// {
//     // Prevent double-application
//     $existing = JobApplication::where('job_id', $id)
//                 ->where('user_id', Auth::id())
//                 ->first();

//     if ($existing) {
//         return back()->with('error', 'You have already applied to this job.');
//     }

//     JobApplication::create([
//         'job_id' => $id,
//         'user_id' => Auth::id(),
//         'status' => 'Pending', // default status
//         'cover_letter' => $request->input('cover_letter', null),
//     ]);
//     $jobPost = JobPost::find($request->job_id);
//     $recruiterId = $jobPost->company_id; // You may need to adjust if your relation is different

//     // Create Notification
//     Notification::create([
//         'user_id' => $recruiterId, // HR user
//         'type' => 'new_application',
//         'content' => 'A new applicant applied for ' . $jobPost->title,
//         'company_logo_url' => null, // Optional: set a logo if you have
//         'is_read' => 0,
//         'created_at' => now(),
//         'updated_at' => now()
//     ]);


//     return redirect()->route('applicantdashboard')->with('success', 'Your test application has been submitted.');

// }

public function apply(Request $request, $id)
{
    $userId = Auth::id();
    $jobPost = JobPost::with('assessment')->findOrFail($id);

    // Prevent duplicate applications
    if (JobApplication::where('job_id', $id)->where('user_id', $userId)->exists()) {
        return back()->with('error', 'You have already applied to this job.');
    }

    // Decide application status
    $status = $jobPost->assessment ? 'under_assessment' : 'pending';

    // Create the application
    $application = JobApplication::create([
        'job_id' => $id,
        'user_id' => $userId,
        'status' => $status,
        'cover_letter' => $request->input('cover_letter', null),
    ]);
     if ($jobPost->assessment) {
        return redirect()->route('assessments.take', [
            'job' => $jobPost->id,
            'application' => $application->id,
        ]);
    }

    // Send notification to recruiter
    $recruiterUsers = \App\Models\User::where('company_id', $jobPost->company_id)->get();

    foreach ($recruiterUsers as $recruiter) {
        $existingNotification = Notification::where('user_id', $recruiter->id)
            ->where('type', 'new_application')
            ->where('is_read', 0)
            ->where('content', 'like', "%{$jobPost->title}%")
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($existingNotification) {
            preg_match('/^(\d+)\s+applicants/', $existingNotification->content, $matches);
            $currentCount = isset($matches[1]) ? (int)$matches[1] : 1;
            $newCount = $currentCount + 1;

            $existingNotification->update([
                'content' => "{$newCount} applicants applied for {$jobPost->title}",
                'updated_at' => now()
            ]);
        } else {
            Notification::create([
                'user_id' => $recruiter->id,
                'type' => 'new_application',
                'content' => "1 applicant applied for {$jobPost->title}",
                'company_logo_url' => null,
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    // Redirect based on whether assessment is required
    if ($status === 'under_assessment') {
        return redirect()->route('assessments.take', $jobPost->id);
    }

    return redirect()->route('applicantdashboard')->with('success', 'Your application has been submitted.');
}



    public function index()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
    
        // Get only job posts belonging to recruiter's company
        $jobs = \App\Models\JobPost::where('company_id', $companyId)->latest()->get();
    
        return view('recruiter.jobsindex', compact('jobs'));
    }
    public function submitAssessment(Request $request, JobPost $job)
{
    


    $user = Auth::user();
    $assessment = $job->assessment;

    if (!$assessment) {
        abort(404, 'Assessment not found for this job.');
    }

    $request->validate([
        'submission_text' => 'nullable|string',
        'submission_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ]);

    // Check for duplicate submission
    $existing = AssessmentSubmission::where('job_post_id', $job->id)
        ->where('user_id', $user->id)
        ->first();

    if ($existing) {
        return back()->with('error', 'You have already submitted this assessment.');
    }

    $submission = new AssessmentSubmission();
    $submission->job_post_id = $job->id;
    $submission->user_id = $user->id;
    $submission->submission_text = $request->input('submission_text');

    if ($request->hasFile('assessment_file')) {
    $submission->submission_file = $request->file('assessment_file')->store('assessment_submissions', 'public');
}


    $submission->started_at = JobApplication::where('job_id', $job->id)
        ->where('user_id', $user->id)
        ->value('created_at'); // fallback to application time

    $submission->submitted_at = now();
    $submission->save();

    // Update application status to "Waiting_for_review"
    JobApplication::where('job_id', $job->id)
        ->where('user_id', $user->id)
        ->update(['status' => 'Waiting_for_review']);

    return redirect()->route('applicantdashboard')->with('success', 'Assessment submitted successfully!');
}
    
    
}