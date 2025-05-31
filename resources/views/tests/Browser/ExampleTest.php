<?php

use Laravel\Dusk\Browser;
test('basic example', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->screenshot('landingpagetest')
                ->assertSee('Discover Your Workora Match!');
    });
})->group('LandingPage');