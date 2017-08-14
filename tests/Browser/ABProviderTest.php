<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Provider;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ABProviderTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testReadProvider()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Provider')
                    ->pause(2000)
                    ->click('.menu_section>ul>li:nth-child(2)>ul>li:nth-child(1)>a')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/provider');
        });
    }

    public function testCreateProvider()
    {
        $provider = ['Telkomsel', 'Indosat', 'XL', 'Axis', '3 Indonesia', 'BOLT!', 'Smartfren', 'PSN', 'Bakrie Telecom'];

        $random = $provider[rand(0,8)] . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/provider')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('provider_code', $random)
                    ->pause(2000)
                    ->type('provider_name', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee($random);
        });
    }

    public function testValidationProvider()
    {
        $provider = ['Telkomsel', 'Indosat', 'XL', 'Axis', '3 Indonesia', 'BOLT!', 'Smartfren', 'PSN', 'Bakrie Telecom'];

        $random = $provider[rand(0,8)] . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/provider')
                    ->waitFor('#dataTables')
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('provider_code', $random)
                    ->pause(2000)
                    ->type('provider_name', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(2) div code span', 'mohon isi')
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'mohon isi')
                    ->type('provider_code', $random)
                    ->pause(2000)
                    ->type('provider_name', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(2) div code span', 'Provider Code ini sudah ada')
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'Provider ini sudah ada')
                    ->clear('provider_name')
                    ->type('provider_name', 'Example Provider Over By 25 Character')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('.modal-form-add div div form div div:nth-child(3) div code span', 'Terlalu Panjang, Maks 25 Karakter');
        });
    }

    public function testUpdateProvider()
    {
        $provider = ['Telkomsel', 'Indosat', 'XL', 'Axis', '3 Indonesia', 'BOLT!', 'Smartfren', 'PSN', 'Bakrie Telecom'];

        $random = $provider[rand(0,8)] . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->browse(function ($browser) use ($random) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/provider')
                    ->waitFor('#dataTables')
                    ->click('#dataTables>tbody>tr:nth-child(1)>td:nth-last-child(1)>a:nth-child(2)')
                    ->pause(2000)
                    ->clear('#code_provider_update')
                    ->pause(2000)
                    ->type('#code_provider_update', $random)
                    ->pause(2000)
                    ->clear('#name_provider_update')
                    ->pause(2000)
                    ->type('#name_provider_update', $random)
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('#dataTables tbody tr:nth-child(1) td:nth-child(2)',$random);
        });
    }

    public function testDeleteProvider()
    {
        $this->browse(function ($browser){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/provider')
                    ->waitFor('#dataTables');

            $text = strtoupper($browser->text('#dataTables tbody tr:nth-child(1) td:nth-child(2)'));

            $browser->click('#dataTables>tbody>tr:nth-child(1)>td:nth-last-child(1)>a:nth-child(3)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSeeIn('#dataTables tbody tr:nth-child(1) td:nth-child(2)', $text);
        });
    }
}
