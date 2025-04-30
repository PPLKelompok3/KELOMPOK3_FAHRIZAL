<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\JobApplication;
use App\Models\Notification;

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
    ]);

    $data['company_id'] = Auth::user()->company_id;

    // ✅ Decode Tagify JSON string → extract skill names
    $skills = collect(json_decode($request->skills, true))
        ->pluck('value')
        ->filter()
        ->map(fn($skill) => ucfirst(strtolower(trim($skill))))
        ->unique()
        ->values();

    // Save skills as comma-separated or JSON string
    $data['skills'] = $skills->implode(','); // or ->toJson() for JSON storage

    $categoryRaw = $request->input('category');
    $decoded = json_decode($categoryRaw, true);
    $data['category'] = is_array($decoded) && isset($decoded[0]['value'])
        ? $decoded[0]['value']
        : null;



    // ✅ Save job post
    $job = JobPost::create($data);

    // ✅ Store new skills to DB
    foreach ($skills as $skillName) {
        \App\Models\Skill::firstOrCreate(['name' => $skillName]);
    }

    return redirect()->route('dashboard')->with('success', 'Job posted!');
}


public function edit(JobPost $job)
{
    return view('jobsmanagement.edit', compact('job'));
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
    ]);

    // 🔁 Handle skills from Tagify JSON array
    $skills = collect(json_decode($request->skills, true))
        ->pluck('value')
        ->filter()
        ->map(fn($skill) => ucfirst(strtolower(trim($skill))))
        ->unique()
        ->values();

    $data['skills'] = $skills->implode(',');

    // 💾 Optionally update skill list in DB
    foreach ($skills as $skillName) {
        \App\Models\Skill::firstOrCreate(['name' => $skillName]);
    }

    // 🔁 Handle category from Tagify JSON (single object array)
    $categoryRaw = $request->input('category');
    $decoded = json_decode($categoryRaw, true);
    $data['category'] = is_array($decoded) && isset($decoded[0]['value'])
        ? $decoded[0]['value']
        : null;

    // ✅ Update the job
    $job->update($data);

    return redirect()->route('dashboard')->with('success', 'Job updated successfully.');
}


public function destroy(JobPost $job)
{
    $job->delete();
    return redirect()->route('dashboard')->with('success', 'Job deleted!');
}
public function toggleStatus(JobPost $job)
{
    $job->status = $job->status === 'Active' ? 'Not Active' : 'Active';
    $job->save();

    return back()->with('success', 'Job status updated to ' . $job->status);
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
        // 🔥 SKIP checking if already applied (so you can test freely)

        JobApplication::create([
            'job_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'Pending', // default status
            'cover_letter' => $request->input('cover_letter', null),
        ]);

        $jobPost = JobPost::find($id); // Still use $id
$recruiterUsers = \App\Models\User::where('company_id', $jobPost->company_id)
                    ->get(); // 🔥 find all users with same company_id

foreach ($recruiterUsers as $recruiter) {
    Notification::create([
        'user_id' => $recruiter->id, // 🔥 Send to recruiter user id
        'type' => 'new_application',
        'content' => 'A new applicant applied for ' . $jobPost->title,
        'company_logo_url' => null,
        'is_read' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

return redirect()->route('applicantdashboard')->with('success', 'Your test application has been submitted.');

    }
}