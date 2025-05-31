<?php

use Laravel\Dusk\Browser;

test('Job Post', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
        ->assertSee('Discover')
        ->clickLink('Login')
        ->assertPathIs('/login')
        ->assertSee('Welcome')
        ->type('email', 'ilma@gmail.com')
        ->type('password', '12345678')
        ->press('Login')
        ->assertSee('Discover')
        ->clickLink('Post a Job')
        ->waitForText('Create New Job') 
        ->assertPathIs('/create')
        ->assertSee('Create New Job')
        ->type('title', 'Data Analyst')
        ->select('employment_type', 'full_time')
        ->type('location', 'Bandung')
        ->type('skills', 'SQL')
        ->type('category', 'Information Technology')
        ->type('experience_level', '2+ years')
        ->type('salary_min', '5000000')
        ->type('salary_max', '10000000')
        ->type('description', 'We are looking for a data analyst')
        ->press('Post Job')
        ->screenshot('job-post-success');

    });
})->group('ManajemenLowonganKerja');

test('Job Edit', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/jobs')
        ->assertSee('My Job')
        ->clickLink('Edit')
        ->assertPathIs('/jobs/edit/26')
        ->assertSee('Edit')
        ->type('title', 'Data Scientist')
        ->press('Update Job')
        ->screenshot('job-edit-success');

    });
})->group('ManajemenLowonganKerja');

test('Job Delete', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/jobs')
        ->assertSee('My Job')
        ->clickLink('Edit')
        ->assertPathIs('/jobs/edit/27')
        ->assertSee('Edit')
        ->check('#confirmDelete')
        ->press('Delete Job')
        ->pause(2500)
        ->assertDontSee('Data Scientist')
        ->screenshot('delete-job-success');

    });
})->group('ManajemenLowonganKerja');

test('Add Job Category', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
        ->assertSee('Discover')
        ->clickLink('Post a Job')
        ->waitForText('Create New Job') 
        ->assertPathIs('/create')
        ->assertSee('Create New Job')
        ->type('title', 'Data Analyst')
        ->select('employment_type', 'full_time')
        ->type('location', 'Bandung')
        ->type('skills', 'SQL')
        ->type('category', 'Information Technology')
        ->type('experience_level', '2+ years')
        ->type('salary_min', '5000000')
        ->type('salary_max', '10000000')
        ->type('description', 'We are looking for a data analyst')
        ->press('Post Job')
        ->screenshot('add-job-category-success');

    });
})->group('ManajemenLowonganKerja');

test('Deactivate Job', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/jobs')
        ->assertSee('My Job')
        ->clickLink('Edit')
        ->assertPathIs('/jobs/edit/27')
        ->assertSee('Edit')
        ->press('Deactivate Job')
        ->screenshot('deactivate-job-success');

    });
})->group('ManajemenLowonganKerja');
