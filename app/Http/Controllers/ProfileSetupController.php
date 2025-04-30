<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Achievement;
use Illuminate\Support\Facades\Storage;

class ProfileSetupController extends Controller
{
    public function storeUserProfile(Request $request)
{
    $request->validate([
        'location' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string|max:1000',
        'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);
    

    $user = User::find(Auth::id());


    $data = $request->only(['location', 'birth_date', 'phone', 'bio']);

    if ($request->hasFile('cv')) {
        $data['cv_url'] = $request->file('cv')->store('cvs', 'public');
    }
    $user->profile()->updateOrCreate([], $data);


    
    return redirect('/skills');
}
public function storeSkills(Request $request)
{
    $request->validate([
        'skills' => 'nullable|array',
        'skills.*' => 'string|max:255',
    ]);

    $user = User::find(Auth::id());

    $submittedSkills = $request->input('skills', []);

    // Normalize & clean input
    $skillIds = collect($submittedSkills)
        ->map(function ($name) {
            return strtolower(trim($name));
        })
        ->unique()
        ->map(function ($name) {
            return Skill::firstOrCreate(['name' => $name])->id;
        });

    $user->skills()->sync($skillIds);

    return redirect('/education');
    
}
public function storeEducation(Request $request)
{
    $request->validate([
        'education' => 'nullable|array',
        'education.*.institution' => 'nullable|string|max:255',
        'education.*.degree' => 'nullable|string|max:255',
        'education.*.field' => 'nullable|string|max:255',
        'education.*.start_date' => 'nullable|date',
        'education.*.end_date' => 'nullable|date|after_or_equal:education.*.start_date',
        'education.*.description' => 'nullable|string',
    ]);

    $user = User::find(Auth::id());

    // Delete old entries (replace mode)
    $user->educations()->delete();

    foreach ($request->input('education', []) as $entry) {
        $user->educations()->create([
            'institution_name' => $entry['institution'] ?? null,
            'degree' => $entry['degree'] ?? null,
            'field_of_study' => $entry['field'] ?? null,
            'start_date' => $entry['start_date'] ?? null,
            'end_date' => $entry['end_date'] ?? null,
            'description' => $entry['description'] ?? null,
        ]);
        
    }

    return redirect('/experience');
}
public function storeExperience(Request $request)
{
    $request->validate([
        'experience' => 'nullable|array',
        'experience.*.type' => 'required|string',
        'experience.*.title' => 'nullable|string|max:255',
        'experience.*.company_or_org' => 'nullable|string|max:255',
        'experience.*.location' => 'nullable|string|max:255',
        'experience.*.start_date' => 'nullable|date',
        'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
        'experience.*.description' => 'nullable|string',
    ]);

    $user = User::find(Auth::id());

    $user->experiences()->delete(); // Replace mode

    foreach ($request->input('experience', []) as $entry) {
        $user->experiences()->create([
            'type' => $entry['type'] ?? null,
            'title' => $entry['position'] ?? null, 
            'company_or_org' => $entry['organization'] ?? null, 
            'location' => $entry['location'] ?? null,
            'start_date' => $entry['start_date'] ?? null,
            'end_date' => $entry['end_date'] ?? null,
            'description' => $entry['description'] ?? null,
        ]);
    }

    return redirect('/projects');
}


public function storeProjects(Request $request)
{
    
    $request->validate([
        'projects' => 'nullable|array',
        'projects.*.name' => 'required|string|max:255',
        'projects.*.description' => 'nullable|string',
        'projects.*.technologies_used' => 'nullable|string|max:1000',
        'projects.*.url' => 'nullable|url|max:255',
    ]);

    $user = User::find(Auth::id());

    $user->projects()->delete(); // Clear previous projects

    foreach ($request->input('projects', []) as $entry) {
        // ✅ Decode technologies_used into string list
        $techList = collect(json_decode($entry['technologies_used'] ?? '[]'))
                        ->pluck('value')
                        ->implode(', ');
    
        $user->projects()->create([
            'title' => $entry['name'],
            'description' => $entry['description'] ?? null,
            'technologies_used' => $techList,
            'link' => $entry['url'] ?? null,
        ]);
    }
    

    return redirect('/achievements');
}
public function storeAchievements(Request $request)
{
    try {
        $request->validate([
            'achievements' => 'nullable|array',
            'achievements.*.title' => 'required|string|max:255',
            'achievements.*.issuer' => 'nullable|string|max:255',
            'achievements.*.date_awarded' => 'nullable|date',
            'achievements.*.description' => 'nullable|string',
            'achievements.*.certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        dd($e->errors()); // 🔍 See which field failed
    }

    $user = User::find(Auth::id());

    $user->achievements()->delete(); // clear old entries

    foreach ($request->input('achievements', []) as $index => $entry) {
        $certPath = null;

        if ($request->hasFile("achievements.$index.certificate")) {
            $certPath = $request->file("achievements.$index.certificate")->store('certificates', 'public');
        }

        $user->achievements()->create([
            'title' => $entry['title'],
            'issuer' => $entry['issuer'] ?? null,
            'date_awarded' => $entry['date_awarded'] ?? null,
            'description' => $entry['description'] ?? null,
            'certificate' => $certPath,
        ]);
    }

    return redirect('/summary');
}


public function showSummary()
{
    $user = User::with([
        'profile',
        'skills',
        'educations',
        'experiences',
        'projects',
        'achievements'
    ])->find(Auth::id());

    return view('profilebuilder.summary', compact('user'));
}



}