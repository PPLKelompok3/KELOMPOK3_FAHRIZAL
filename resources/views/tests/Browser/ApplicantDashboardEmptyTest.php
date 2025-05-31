<?php

use Laravel\Dusk\Browser;
use App\Models\JobApplication;
use App\Models\User;

test('example', function () {
    $this->browse(function (Browser $browser) {
        $applicant = User::factory()->create([
            'role' => 'applicant',
        ]);
        $browser->loginAs($applicant)
            ->visit('/applicantdashboard')
            ->assertSee("You haven't applied for any jobs yet.");
    });
})->group('EmptyApplicantDashboard');