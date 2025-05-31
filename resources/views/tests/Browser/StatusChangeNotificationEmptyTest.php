<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('example', function () {
    $this->browse(function (Browser $browser) {
        // Create applicant
        $applicant = User::factory()->create([
            'role' => 'applicant', 
        ]);
        $browser->logout()
            ->loginAs($applicant)
            ->visit('/applicantnotification')
            ->assertSee('No notifications yet 📭');
    });
})->group('EmptyNotificationTest');