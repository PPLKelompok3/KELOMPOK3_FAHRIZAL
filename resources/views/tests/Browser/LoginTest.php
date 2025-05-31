<?php

use Laravel\Dusk\Browser;

test('example', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->click('@login-button')
                ->screenshot("loginpage")
                ->type('email', 'applicant2@gmail.com')
                ->type('password','12345678')
                ->press('@LoginButton')
                ->assertSee('Workora');
                
                
    });
});