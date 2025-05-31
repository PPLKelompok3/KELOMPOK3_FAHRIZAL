<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NegatifRekomendasiTest extends DuskTestCase
{
    public function testNegatifRekomendasi()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'ayesaimut@gmail.com') // ganti dengan email valid
                    ->type('password', '12345678') // ganti dengan password valid
                    ->press('Login')

                    // Click Canva skill tag (add dusk attribute if needed)
                    ->clickLink('Canva')
                    ->pause(1000)

                    // Check if the job appears
                    ->assertDontSee('Cloud Engineer');
        });
    }
}
