<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProviderTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRead()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Provider')
                    ->pause(2000)
                    ->click('.menu_section>ul>li:nth-child(2)>ul>li:nth-child(1)>a')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/provider');
        });
    }

    public function testCreate()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('input[name=provider_name]', 'Example Provider')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee('Example Provider');
        });
    }

    public function testValidationEmpty()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee('mohon isi');
        });
    }

    public function testValidationExist()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('input[name=provider_name]', 'Example Provider')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee('Provider ini sudah ada');
        });
    }

    public function testEdit()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->waitFor('#dataTables')
                    ->click('#dataTables>tbody>tr:nth-last-child(1)>td:nth-last-child(1)>a:nth-child(2)')
                    ->pause(2000)
                    ->clear('#name_provider_update')
                    ->pause(2000)
                    ->type('#name_provider_update', 'Example Provider Edited')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee('Example Provider Edited');
        });
    }

    public function testDelete()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->waitFor('#dataTables')
                    ->click('#dataTables>tbody>tr:nth-last-child(1)>td:nth-last-child(1)>a:nth-child(3)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSee('Example Provider Edited');
        });
    }
}
