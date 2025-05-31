<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\User;
use App\Models\Skill;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


class UserProfileEditController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('profilebuilder.userprofileedit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture_cropped' => 'nullable|string',
        ]);

        $user = Auth::user();

       if ($request->filled('profile_picture_cropped')) {
    $base64 = $request->input('profile_picture_cropped');
    $image = ImageManager::gd()->read($base64)->toJpeg(90);

    $filename = 'profiles/' . uniqid() . '.jpg';
    Storage::disk('public')->put($filename, (string) $image);
    /** @var \App\Models\User $user */
    $profile = $user->profile ?? $user->profile()->create();
    $profile->profile_picture = $filename;
    $profile->save();
}


        $data = $request->only(['location', 'birth_date', 'phone', 'bio']);

        if ($request->hasFile('cv')) {
            $data['cv_url'] = $request->file('cv')->store('cvs', 'public');
        }
        /** @var \App\Models\User $user */
        $user->profile()->updateOrCreate([], $data);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    public function editexperience()
    {
    $user = Auth::user();
    /** @var \App\Models\User $user */
    $experiences = $user->experiences()->orderBy('start_date', 'desc')->get();

    return view('profilebuilder.experienceedit', compact('experiences'));
    }
    public function updateExperience(Request $request)
{
    $request->validate([
        'experience' => 'nullable|array',
        'experience.*.type' => 'required|string|in:internship,full_time,part_time,freelance,organization',
        'experience.*.position' => 'nullable|string|max:255',
        'experience.*.organization' => 'nullable|string|max:255',
        'experience.*.location' => 'nullable|string|max:255',
        'experience.*.start_date' => 'nullable|date',
        'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
        'experience.*.description' => 'nullable|string',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Clear all existing experience entries
    $user->experiences()->delete();

    // Store the new ones
    foreach ($request->input('experience', []) as $entry) {
        $user->experiences()->create([
            'type'           => $entry['type'] ?? null,
            'title'          => $entry['position'] ?? null,
            'company_or_org' => $entry['organization'] ?? null,
            'location'       => $entry['location'] ?? null,
            'start_date'     => $entry['start_date'] ?? null,
            'end_date'       => $entry['end_date'] ?? null,
            'description'    => $entry['description'] ?? null,
        ]);
    }

    return redirect()->route('profile.show')->with('success', 'Experience updated successfully.');
}
public function editSkills()
{
    $user = Auth::user();
    /** @var \App\Models\User $user */
    $skillNames = $user->skills()->pluck('name')->toArray();
    return view('profilebuilder.skillsedit', compact('skillNames'));
}

public function updateSkills(Request $request)
{
    $request->validate([
        'skills' => 'nullable|array',
        'skills.*' => 'string|max:255',
    ]);

    $user = Auth::user();
    $submittedSkills = $request->input('skills', []);

    $skillIds = collect($submittedSkills)
        ->map(fn($name) => strtolower(trim($name)))
        ->unique()
        ->map(fn($name) => Skill::firstOrCreate(['name' => $name])->id);
    /** @var \App\Models\User $user */
    $user->skills()->sync($skillIds);

    return redirect()->route('profile.show')->with('success', 'Skills updated.');
}


public function editProjects()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $projects = $user->projects()->get();
    return view('profilebuilder.projectsedit', compact('projects'));
}

public function updateProjects(Request $request)
{
    $request->validate([
        'projects' => 'nullable|array',
        'projects.*.name' => 'required|string|max:255',
        'projects.*.description' => 'nullable|string',
        'projects.*.technologies_used' => 'nullable|string|max:1000',
        'projects.*.url' => 'nullable|url|max:255',
    ]);
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $user->projects()->delete();

    foreach ($request->input('projects', []) as $entry) {
        $techList = collect(json_decode($entry['technologies_used'] ?? '[]'))->pluck('value')->implode(', ');

        $user->projects()->create([
            'title' => $entry['name'],
            'description' => $entry['description'] ?? null,
            'technologies_used' => $techList,
            'link' => $entry['url'] ?? null,
        ]);
    }

    return redirect()->route('profile.show')->with('success', 'Projects updated successfully.');
}
public function editEducation()
{
    $user = Auth::user();
    $educations = $user->educations;
    return view('profilebuilder.educationedit', compact('educations'));
}

public function updateEducation(Request $request)
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

    $user = Auth::user();
    /** @var \App\Models\User $user */
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

    return redirect()->route('profile.show')->with('success', 'Education updated successfully.');
}
public function editAchievements()
{
    $user = Auth::user();
    $achievements = $user->achievements;

    return view('profilebuilder.achievementsedit', compact('achievements'));
}

public function updateAchievements(Request $request)
{
    $request->validate([
        'achievements' => 'nullable|array',
        'achievements.*.title' => 'required|string|max:255',
        'achievements.*.issuer' => 'nullable|string|max:255',
        'achievements.*.date_awarded' => 'nullable|date',
        'achievements.*.description' => 'nullable|string',
        'achievements.*.certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user();
    $user->achievements()->delete(); // Replace old entries

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

    return redirect()->route('profile.show')->with('success', 'Achievements updated successfully.');
}


}