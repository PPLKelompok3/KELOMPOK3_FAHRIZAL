<?php

use App\Models\User;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Company;
use Laravel\Dusk\Browser;

test('applicant sees notification after status change', function () {
    $this->browse(function (Browser $browser) {

        // Create recruiter tied to that company
        $recruiter = User::factory()->create([
            'role' => 'recruiter',
            'company_id' => 5,
        ]);

        // Create applicant
        $applicant = User::factory()->create([
            'role' => 'applicant',
        ]);

        // Create job post
        $jobPost = JobPost::factory()->create([
            'company_id' => $recruiter->company_id,
            'title' => 'Full Stack Developer'
        ]);

        // Create application
        $application = JobApplication::factory()->create([
            'user_id' => $applicant->id,
            'status' => 'Pending',
            'job_id' => $jobPost->id,
        ]);

        // Step 1: Recruiter updates status to 'Interview'
        $browser->loginAs($recruiter)
            ->visit('/recruiter/applications')
            ->click("@view-application-{$application->id}")
            ->pause(1000)
            ->select('status', 'Interview')
            ->press('@apply-status-button')
            ->waitForText('Interview')
            ->assertSeeIn('#current-status', 'Interview');

        // Step 2: Applicant logs in and checks notification
        $browser->logout()
            ->loginAs($applicant)
            ->visit('/applicantnotification')
            ->assertSee('Your application for "Full Stack Developer" at "Telkom Indonesia" is now "Interview".');
    });
})->group('NotificationTest');