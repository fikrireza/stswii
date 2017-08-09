<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\ProviderPrefix;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProviderPrefixTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRead()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::first())
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Provider')
                    ->pause(2000)
                    ->clickLink('Provider Prefix')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/provider-prefix');
        });
    }

    public function testCreate()
    {
        $random = str_pad(rand(800, 999), 4, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random) {
            $browser->loginAs(User::first())
                    ->visit('/provider-prefix')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->select('provider_id')
                    ->pause(2000)
                    ->type('prefix', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee($random);
        });
    }

    public function testValidation()
    {
        $random = str_pad(rand(8000, 8999), 5, '0', STR_PAD_LEFT);
        $text = 'prefix number';
        $over = '012345678901234567890';

        $this->browse(function ($browser) use ($random, $text, $over) {
            $browser->loginAs(User::first())
                    ->visit('/provider-prefix')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->select('provider_id')
                    ->pause(2000)
                    ->type('#prefix', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(2) div code span', 'mohon isi')
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'mohon isi')
                    ->select('#provider_id')
                    ->pause(2000)
                    ->type('#prefix', '012345678901234567890')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'Prefix harus 1 sampai 18 digit');
        });
    }

    public function testEdit()
    {
        $random = str_pad(rand(8000, 8999), 5, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random){
            $browser->loginAs(User::first())
                    ->visit('/provider-prefix')
                    ->waitFor('#dataTables')
                    ->click('#dataTables>tbody>tr:nth-child(1)>td:nth-last-child(1)>a:nth-child(1)')
                    ->pause(2000)
                    ->select('#provider_id_update')
                    ->pause(2000)
                    ->type('#prefix_update', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee($random);
        });
    }

    public function testDelete()
    {
        $providerPrefix = ProviderPrefix::first();

        $this->browse(function ($browser) use ($providerPrefix){
            $browser->loginAs(User::first())
                    ->visit('/provider-prefix')
                    ->waitFor('#dataTables')
                    ->click('#dataTables>tbody>tr:nth-child(1)>td:nth-last-child(1)>a:nth-child(2)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSee($providerPrefix->prefix);
        });
    }
}
