<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\ProviderPrefix;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ACProviderPrefixTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testReadProviderPrefix()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Provider')
                    ->pause(2000)
                    ->clickLink('Provider Prefix')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/provider-prefix');
        });
    }

    public function testCreateProviderPrefix()
    {
        $random = str_pad(rand(800, 999), 4, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
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

    public function testValidationProviderPrefix()
    {
        $random = str_pad(rand(8000, 8999), 5, '0', STR_PAD_LEFT);
        $text = 'prefix number';
        $over = '012345678901234567890';

        $this->browse(function ($browser) use ($random, $text, $over) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/provider-prefix')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->select('provider_id')
                    ->pause(2000)
                    ->type('#prefix', $random)
                    ->pause(2000);

            $provider_id = $browser->value('select[name=provider_id]');

            $browser->press('Submit')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(2) div code span', 'mohon isi')
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'mohon isi')
                    ->select('#provider_id', $provider_id)
                    ->pause(2000)
                    ->type('#prefix', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'Prefix ini sudah ada')
                    ->type('#prefix', '012345678901234567890')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'Prefix harus 1 sampai 18 digit');
        });
    }

    public function testUpdateProviderPrefix()
    {
        $random = str_pad(rand(8000, 8999), 5, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
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
                    ->assertSeeIn('#dataTables tbody tr:nth-child(1) td:nth-child(2)', $random);
        });
    }

    public function testDeleteProviderPrefix()
    {
        $this->browse(function ($browser){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/provider-prefix')
                    ->waitFor('#dataTables');

            $text = strtoupper($browser->text('#dataTables tbody tr:nth-child(1) td:nth-child(3)'));

            $browser->click('#dataTables>tbody>tr:nth-child(1)>td:nth-last-child(1)>a:nth-child(2)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSeeIn('#dataTables tbody tr:nth-child(1) td:nth-child(3)', $text);
        });
    }
}
