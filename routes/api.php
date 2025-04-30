<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ExperienceOptionController;
use App\Http\Controllers\Api\TechnologyController;
use App\Models\JobPost;

// For job titles
Route::get('/job-posts/titles', function () {
    return JobPost::select('title')->distinct()->pluck('title')->map(fn($t) => ['value' => $t]);
});

// For locations
Route::get('/job-posts/locations', function () {
    return JobPost::select('location')->distinct()->whereNotNull('location')->pluck('location')->map(fn($l) => ['value' => $l]);
});

// For employment types
Route::get('/job-posts/types', function () {
    return JobPost::select('employment_type')->distinct()->pluck('employment_type')->map(fn($t) => ['value' => ucfirst(str_replace('_', ' ', $t))]);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/skills/search', [SkillController::class, 'search']);

use App\Http\Controllers\Api\EducationMetaController;

Route::get('/educations/institutions/search', [EducationMetaController::class, 'searchInstitutions']);
Route::get('/educations/degrees/search', [EducationMetaController::class, 'searchDegrees']);
Route::get('/educations/fields/search', [EducationMetaController::class, 'searchFields']);


Route::get('/experiences/organizations/search', [ExperienceOptionController::class, 'searchOrganizations']);
Route::get('/experiences/titles/search', [ExperienceOptionController::class, 'searchTitles']);

Route::get('/projects/technologies/search', [TechnologyController::class, 'autocomplete']);

Route::get('/companies/search', function (Request $request) {
    $query = $request->q;

    $companies = \App\Models\Company::where('company_name', 'like', "%{$query}%")
        ->select('company_name')
        ->distinct()
        ->get();

    return response()->json($companies);
});
Route::get('/api/skills/search', function (Request $request) {
    $query = $request->get('q', '');
    $skills = \App\Models\Skill::where('name', 'LIKE', "%$query%")
        ->pluck('name')
        ->map(fn($skill) => ['value' => $skill])
        ->values();

    return response()->json($skills);
});

// For job titles
Route::get('/job-posts/titles', function () {
    return JobPost::select('title')->distinct()->pluck('title')->map(fn($t) => ['value' => $t]);
});

// For locations
Route::get('/job-posts/locations', function () {
    return JobPost::select('location')->distinct()->whereNotNull('location')->pluck('location')->map(fn($l) => ['value' => $l]);
});

// For employment types
Route::get('/job-posts/types', function () {
    return JobPost::select('employment_type')->distinct()->pluck('employment_type')->map(fn($t) => ['value' => ucfirst(str_replace('_', ' ', $t))]);
});
