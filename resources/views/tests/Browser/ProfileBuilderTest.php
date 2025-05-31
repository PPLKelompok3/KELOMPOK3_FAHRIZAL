<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileBuilderTest extends DuskTestCase
{
    public function testProfileBuilder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Login') // step
                ->clickLink('Register here') // step 2
                ->screenshot('step1-register-page')
                ->type('name', 'Test User') // step 3
                ->type('email', 'testprofile10@example.com')
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
                ->press('Continue');

                // Step 5: Skills
            // Execute script separately
            $browser->pause(1500) // wait for Tagify JS to load and bind
            ->screenshot('before-skills')
            ->script(["document.querySelector('#skills-input').value = 'Communication';",
            "document.querySelector('#skills-input').dispatchEvent(new Event('input', { bubbles: true }));",
            "document.querySelector('#skills-input').dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));",
            ]);
                
            // Resume the browser chain
            $browser->pause(500)
                ->screenshot('step3-skill-page')
                ->press('Continue')

                // Step 6: Education
                ->type('education[0][institution]', 'Universitas Gadjah Mada')
                ->pause(1500)
                ->keys('input[name=education\\[0\\]\\[institution\\]]', '{ENTER}')
                ->type('education[0][degree]', 'S1')
                ->pause(1500)
                ->keys('input[name=education\\[0\\]\\[degree\\]]', '{ENTER}')
                ->type('education[0][field]', 'Information Systems')
                ->pause(1500)
                ->keys('input[name=education\\[0\\]\\[field\\]]', '{ENTER}')
                ->type('education[0][start_date]', '08-01-2023')
                ->type('education[0][end_date]', '07-01-2023')
                ->type('education[0][description]', 'Undergraduate Thesis on Web Dev')
                ->screenshot('step4-education-page')
                ->press('Continue')

                // Step 7: Work Experience
                ->type('experience[0][organization]', 'PT Digital Solusi')
                ->pause(1500)
                ->keys('input[name=experience\\[0\\]\\[organization\\]]', '{ENTER}')
                ->type('experience[0][position]', 'Intern Developer')
                ->pause(1500)
                ->keys('input[name=experience\\[0\\]\\[position\\]]', '{ENTER}')
                ->select('experience[0][type]', 'internship') // must match the value, not label
                ->type('experience[0][location]', 'Jakarta')
                ->type('experience[0][start_date]', '07-01-2022') // HTML5 date format
                ->type('experience[0][end_date]', '10-01-2022')
                ->type('experience[0][description]', 'Worked on Laravel APIs')
                ->screenshot('step5-experience-page')
                ->press('Continue')

                // Step 8: Projects
                ->type('projects[0][name]', 'My Portfolio')
                ->type('projects[0][description]', 'A website to showcase my work');

            $browser->script([
                "document.querySelector('input[name=\"projects[0][technologies_used]\"]').value = 'Laragon, Bootstrap';",
                "document.querySelector('input[name=\"projects[0][technologies_used]\"]').dispatchEvent(new Event('input', { bubbles: true }));",
                "document.querySelector('input[name=\"projects[0][technologies_used]\"]').dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));",
            ]);
            
            $browser->pause(500)
                ->type('projects[0][url]', 'https://github.com/test/portfolio')
                ->screenshot('step6-projects-page')
                ->press('Continue')

                // Step 9: Achievements
                ->type('achievements[0][title]', 'Best Intern 2022')
                ->type('achievements[0][issuer]', 'Kampus Merdeka')
                ->type('achievements[0][date_awarded]', '2023-08-01') // Must be in YYYY-MM-DD format
                ->attach('achievements[0][certificate]', __DIR__.'/files/dummy_certificate.pdf')
                ->type('achievements[0][description]', 'Awarded for outstanding performance during internship')
                ->screenshot('step7-achievements-page')
                ->press('Continue')

                // Step 10: Profile Summary
                ->assertSee('Profile Summary')
                ->assertSee('testprofile10@example.com')
                ->assertSee('Yogyakarta')
                ->assertSee('Laravel')
                ->screenshot('summary-page');
        });
    }
}