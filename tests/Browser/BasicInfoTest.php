<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BasicInfoTest extends DuskTestCase
{
    public function testBasicInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Login') // step
                ->clickLink('Register here') // step 2
                ->screenshot('step1-register-page')
                ->type('name', 'Test User') // step 3
                ->type('email', 'testprofile11@example.com')
                ->select('role', 'applicant')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')

                // Step 4: Basic Info
                ->type('location', 'Yogyakarta, Yogyakarta City, Special Region of Yogyakarta, Indonesia')
                ->pause(1000) // wait for Google Maps suggestions
                ->keys('input[name=location]', '{ARROW_DOWN}', '{ENTER}')
                ->type('birth_date', '01/01/2000')
                ->type('phone', '081234567890')
                ->attach('cv', __DIR__.'/files/dummy_cv.pdf')
                ->type('bio', 'This is a short bio')
                ->screenshot('step2-basic-info')
                ->press('Continue')

                ->waitForText('Step 2: Skills', 5)
                ->assertSee('Step 2: Skills')
                ->screenshot('step3-skill-page');
        });
    }
}