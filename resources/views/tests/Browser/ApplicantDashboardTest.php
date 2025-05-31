<?php

use Laravel\Dusk\Browser;
use App\Models\JobApplication;
use App\Models\User;

test('example', function () {
    $this->browse(function (Browser $browser) {
        $applicant = User::factory()->create([
            'role' => 'applicant',
        ]);
        JobApplication::factory()->create([
            'user_id' => $applicant->id,
            'job_id' => '14',
            'status' => 'Pending'
        ]);
        $browser->loginAs($applicant)
            ->visit('/applicantdashboard')
            ->assertSee('Cloud Engineer')
            ->assertSee('Telkom Indonesia')
            ->assertSee('PENDING');
    });
})->group('ApplicantDashboard');