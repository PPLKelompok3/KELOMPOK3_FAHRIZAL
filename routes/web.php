<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecruiterDashboardController;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/laravel', function () {
    return view('welcome');
});


Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/ajax/jobs', [LandingController::class, 'jobListPartial'])->name('ajax.jobs');
Route::get('/jobs/{id}/related', [JobPostController::class, 'relatedJobs'])->name('jobs.related');

Route::post('/jobs/{id}/apply', [JobPostController::class, 'apply'])->middleware('auth')->name('jobs.apply');







Route::middleware(['auth', 'applicant'])->group(function () {
    Route::get('/userprofile', fn() => view('profilebuilder/userprofile'));
    Route::post('/userprofile/store', [ProfileSetupController::class, 'storeUserProfile'])->name('userprofile.store');

    Route::get('/skills', fn() => view('profilebuilder/skills'));
    Route::post('/skills/store', [ProfileSetupController::class, 'storeSkills'])->name('skills.store');

    Route::get('/education', fn() => view('profilebuilder/education'));
    Route::post('/education/store', [ProfileSetupController::class, 'storeEducation'])->name('education.store');

    Route::get('/experience', fn() => view('profilebuilder/experience'));
    Route::post('/experience/store', [ProfileSetupController::class, 'storeExperience'])->name('experience.store');

    Route::get('/projects', fn() => view('profilebuilder/projects'));
    Route::post('/projects/store', [ProfileSetupController::class, 'storeProjects'])->name('projects.store');

    Route::get('/achievements', fn() => view('profilebuilder/achievement'));
    Route::post('/achievements/store', [ProfileSetupController::class, 'storeAchievements'])->name('achievements.store');

    Route::get('/summary', [ProfileSetupController::class, 'showSummary'])->name('summary');
    Route::get('/applicantdashboard', [ApplicantController::class, 'appliedJobs'])->name('applicantdashboard');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/applicantnotification', [NotificationController::class, 'index'])->name('applicantnotification');
});

// Recruiter-only access
Route::middleware(['auth', 'recruiter'])->group(function () {
    Route::get('/recruiter/profile', [RecruiterController::class, 'showProfileForm'])->name('recruiter.profile');
    Route::post('/recruiter/profile/store', [RecruiterController::class, 'storeProfile'])->name('recruiter.profile.store');

    // Main Dashboard Page Route
    Route::get('/dashboard', [RecruiterDashboardController::class, 'recruiterDashboard'])->name('recruiter.dashboard');

    // Graph Data API Route
    Route::get('/recruiter/dashboard/job-applicants-graph', [RecruiterDashboardController::class, 'getJobApplicantsGraph']);

    Route::post('/recruiter/company/check', [RecruiterController::class, 'checkCompany'])->name('recruiter.company.check');
    Route::post('/recruiter/profile/store', [RecruiterController::class, 'storeProfile'])->name('recruiter.profile.store');
    Route::get('/recruiter/applications', [RecruiterController::class, 'applications'])->name('applications.index');
    Route::get('/recruiter/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/recruiter/applications/{application}/update-status', [JobApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});



Route::middleware(['auth', 'recruiter'])->group(function () {
    // Route::get('/', [JobPostController::class, 'index'])->name('jobs.index');
    Route::get('/create', [JobPostController::class, 'create'])->name('jobs.create');
    Route::post('/store', [JobPostController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [JobPostController::class, 'edit'])->name('jobs.edit');
    Route::put('/{job}', [JobPostController::class, 'update'])->name('jobs.update');
    Route::delete('/{job}', [JobPostController::class, 'destroy'])->name('jobs.destroy');
    Route::get('/createjob', fn() => view('jobsmanagement/Create'));
    Route::get('/jobs/edit/{job}', [JobPostController::class, 'edit'])->name('jobs.edit');
    Route::patch('/jobs/{job}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('jobs.toggle-status');
    Route::patch('/applications/{id}/update-status', [JobApplicationController::class, 'updateStatus'])->name('applications.update-status');
});
///TEMPORARY///


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/jobs/{id}/{slug?}', [JobPostController::class, 'show'])->name('jobs.show');

require __DIR__ . '/auth.php';
