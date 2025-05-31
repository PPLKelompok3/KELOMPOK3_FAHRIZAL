<?php
use App\Models\User;
use App\Models\JobApplication;
use App\Models\JobPost;
use Laravel\Dusk\Browser;

test('example', function () {
    $this->browse(function (Browser $browser) {
        // Create recruiter
        $recruiter = User::factory()->create([
            'role' => 'recruiter',
            'company_id' => 5, // ensure this matches the job's company_id
        ]);
        $applicant = User::factory()->create([
            'role' => 'applicant',
        ]);

        // Create application for that company
        $jobPost = JobPost::factory()->create(['company_id' => $recruiter->company_id]);        
        $application = JobApplication::factory()->create([
            'user_id' => $applicant->id,
            'status' => 'Pending',
            'job_id' => $jobPost->id, 
        ]);

        $browser->loginAs($recruiter)
            ->visit('/dashboard')
            ->visit('/recruiter/applications') // Or visit the exact route
            ->click("@view-application-{$application->id}") // Add dusk attribute if not present
            ->pause(1000)
            ->select('status', 'Interview') // assuming 'status' is the name of the select field
            ->press('@apply-status-button') // Add dusk selector to the Apply Status button
            ->waitForText('Interview')
            ->assertSeeIn('#current-status', 'Interview');
    });
})->group('StatusChangeTest');