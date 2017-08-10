<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\Login;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Login)
                    ->loginForm()
                    ->assertPathIs('/stswii/public/home');
        });
    }

}
