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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Models\Notification;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Route;

Route::get('/laravel', function () {
    return view('welcome');
});
Route::get('/breezedashboard', function () {
    return view('dashboard');
})->name('breezedashboard');


Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/ajax/jobs', [LandingController::class, 'jobListPartial'])->name('ajax.jobs');
Route::get('/jobs/{id}/related', [JobPostController::class, 'relatedJobs'])->name('jobs.related');

Route::post('/jobs/{id}/apply', [JobPostController::class, 'apply'])->middleware('auth')->name('jobs.apply');
Route::get('/assessments/{job}/{application}', [JobPostAssessmentController::class, 'show'])->name('assessments.take');
Route::post('/jobs/{job}/assessment/submit', [JobPostController::class, 'submitAssessment'])
        ->name('assessment.submit');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admindashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/verify-recruiter/{id}', [AdminController::class, 'verifyRecruiter'])->name('admin.verifyRecruiter');
});


Route::resource('articles', ArticleController::class);
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles/{id}/comment', [ArticleController::class, 'storeComment'])->name('article.comment');

Route::get('/admin/articles/verification', [ArticleController::class, 'verifyArticles'])->name('admin.articles.verify');
Route::get('/admin/articles/articlelist', [ArticleController::class, 'listArticles'])->name('admin.articles.articlelist');
Route::patch('/admin/articles/{id}/approve', [ArticleController::class, 'approveArticle'])->name('admin.articles.approve');
Route::get('/admin/articles/admin-published', [ArticleController::class, 'adminPublishedArticles'])->name('admin.articles.adminPublished');

Route::middleware(['auth'])->prefix('profile/edit')->group(function () {
    Route::get('/basic-info', [UserProfileEditController::class, 'edit'])->name('userprofile.edit');
    Route::post('/basic-info', [UserProfileEditController::class, 'update'])->name('userprofile.update');
    Route::get('/experience', [UserProfileEditController::class, 'editexperience'])->name('experience.edit');
    Route::post('/experience', [UserProfileEditController::class, 'updateExperience'])->name('experience.update');
    Route::get('/skills', [UserProfileEditController::class, 'editSkills'])->name('skills.edit');
    Route::post('/skills', [UserProfileEditController::class, 'updateSkills'])->name('skills.update');
    Route::get('/projects', [UserProfileEditController::class, 'editProjects'])->name('projects.edit');
    Route::post('/projects', [UserProfileEditController::class, 'updateProjects'])->name('projects.update');
    Route::get('/education', [UserProfileEditController::class, 'editEducation'])->name('education.edit');
    Route::post('/education', [UserProfileEditController::class, 'updateEducation'])->name('education.update');
    Route::get('/profile/edit/achievements', [UserProfileEditController::class, 'editAchievements'])->name('achievements.edit');
    Route::post('/profile/edit/achievements', [UserProfileEditController::class, 'updateAchievements'])->name('achievements.update');



});

Route::view('/assessment-preview', 'assessment-static');
Route::view('/assessment-file', 'assessment-file');



Route::get('/profilepage', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');



Route::middleware(['auth', 'applicant'])->group(function () {
    Route::get('/userprofile', fn () => view('profilebuilder/userprofile'));
    Route::post('/userprofile/store', [ProfileSetupController::class, 'storeUserProfile'])->name('userprofile.store');

    Route::get('/skills', fn () => view('profilebuilder/skills'));
    Route::post('/skills/store', [ProfileSetupController::class, 'storeSkills'])->name('skills.store');

    Route::get('/education', fn () => view('profilebuilder/education'));
    Route::post('/education/store', [ProfileSetupController::class, 'storeEducation'])->name('education.store');

    Route::get('/experience', fn () => view('profilebuilder/experience'));
    Route::post('/experience/store', [ProfileSetupController::class, 'storeExperience'])->name('experience.store');

    Route::get('/projects', fn () => view('profilebuilder/projects'));
    Route::post('/projects/store', [ProfileSetupController::class, 'storeProjects'])->name('projects.store');

    Route::get('/achievements', fn () => view('profilebuilder/achievement'));
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

    

});
    


Route::middleware(['auth', 'verified.recruiter'])->group(function () {
    // Route::get('/', [JobPostController::class, 'index'])->name('jobs.index');
    // Main Dashboard Page Route
    Route::get('/dashboard', [RecruiterDashboardController::class, 'recruiterDashboard'])->name('recruiter.dashboard');
    
    // Graph Data API Route
    Route::get('/recruiter/dashboard/job-applicants-graph', [RecruiterDashboardController::class, 'getJobApplicantsGraph']);
    
    Route::post('/recruiter/company/check', [RecruiterController::class, 'checkCompany'])->name('recruiter.company.check');
    Route::post('/recruiter/profile/store', [RecruiterController::class, 'storeProfile'])->name('recruiter.profile.store');
    // Route::get('/recruiter/applications', [RecruiterController::class, 'applications'])->name('applications.index');
    Route::get('/recruiter/jobs/{id}/applications', [RecruiterController::class, 'applicationsByJob'])->name('recruiter.applications.byJob');
    Route::get('/recruiter/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/recruiter/applications/{application}/update-status', [JobApplicationController::class, 'updateStatus'])->name('applications.updateStatus');

});
    

Route::middleware(['auth', 'verified.recruiter'])->group(function () {
    // Route::get('/', [JobPostController::class, 'index'])->name('jobs.index');
    Route::get('/create', [JobPostController::class, 'create'])->name('jobs.create');
    Route::post('/store', [JobPostController::class, 'store'])->name('jobs.store');
    Route::get('/jobs', [JobPostController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}/edit', [JobPostController::class, 'edit'])->name('jobs.edit');
    Route::put('/{job}', [JobPostController::class, 'update'])->name('jobs.update');
    Route::delete('/{job}', [JobPostController::class, 'destroy'])->name('jobs.destroy');
    Route::get('/createjob', fn () => view('jobsmanagement/Create'));
    Route::get('/jobs/edit/{job}', [JobPostController::class, 'edit'])->name('jobs.edit');
    Route::patch('/jobs/{job}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('jobs.toggle-status');
    Route::patch('/applications/{id}/update-status', [JobApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/recruiter/notifications', [NotificationController::class, 'recruiterIndex'])->name('recruiter.notifications');

    

});
///TEMPORARY///

Route::get('/recruiter/unverified', function () {
    return view('recruiter.unverified');
})->name('recruiter.unverified');






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/jobs/{id}/{slug?}', [JobPostController::class, 'show'])->name('jobs.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/articles/{id}/toggle-favorite', [ArticleController::class, 'toggleFavorite'])->name('articles.toggleFavorite');
    Route::get('/articles/favorites', [ArticleController::class, 'favoriteList'])->name('articles.favorites');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
});

Route::resource('articles', ArticleController::class);
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::post('/articles/{id}/comment', [ArticleController::class, 'storeComment'])->name('article.comment');

Route::get('/admin/articles/verification', [ArticleController::class, 'verifyArticles'])->name('admin.articles.verify');
Route::get('/admin/articles/articlelist', [ArticleController::class, 'listArticles'])->name('admin.articles.articlelist');
Route::patch('/admin/articles/{id}/approve', [ArticleController::class, 'approveArticle'])->name('admin.articles.approve');
Route::get('/admin/articles/admin-published', [ArticleController::class, 'adminPublishedArticles'])->name('admin.articles.adminPublished');



require __DIR__.'/auth.php';