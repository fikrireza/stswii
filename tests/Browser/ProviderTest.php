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
                    ->clickLink('Manage Provider')
                    ->click('.menu_section>ul>li:nth-child(2)>ul>li:nth-child(1)>a')
                    ->assertPathIs('/stswii/public/provider');
        });
    }

    public function testCreate()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->clickLink('Add')
                    ->type('input[name=provider_name]', 'Example Provider')
                    ->press('Submit')
                    ->assertSee('Example Provider');
        });
    }

    public function testValidation()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::find(3))
                    ->visit('/provider')
                    ->clickLink('Add')
                    ->type('input[name=provider_name]', 'Example Provider')
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
                    ->clear('#name_provider_update')
                    ->type('#name_provider_update', 'Example Provider Edited')
                    ->press('Submit')
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
                    ->click('#setDelete')
                    ->assertDontSee('Example Provider Edited');
        });
    }
}
