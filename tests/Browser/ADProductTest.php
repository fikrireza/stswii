<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ADProductTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testReadProduct()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Product')
                    ->pause(2000)
                    ->click('.menu_section>ul>li:nth-child(3)>ul>li:nth-child(1)>a')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/product');
        });
    }

    public function testCreateProduct()
    {
        $product = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $type = ['PULSA', 'DATA'];
        $nominal = rand(0,1000);
        $random = $product[rand(0,25)];

        $this->browse(function ($browser) use ($random, $nominal, $type){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/product')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000);

            $browser->select('provider_id')
                    ->pause(2000)
                    ->type('product_code', $random.$nominal)
                    ->pause(2000)
                    ->type('product_name', $random)
                    ->pause(2000)
                    ->type('nominal', $nominal * 1000)
                    ->pause(2000)
                    ->radio('type', $type[rand(0,1)])
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(strtoupper($random.$nominal));
        });
    }

    public function testValidationProduct()
    {
        $product = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $type = ['PULSA', 'DATA'];
        $nominal = rand(0,1000);
        $random = $product[rand(0,25)];

        $this->browse(function ($browser) use ($random, $nominal, $type){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/product')
                    ->pause(2000);

            // first insert
            $browser->clickLink('Add')
                    ->pause(2000)
                    ->select('provider_id')
                    ->pause(2000)
                    ->type('product_code', $random.$nominal)
                    ->pause(2000)
                    ->type('product_name', $random)
                    ->pause(2000)
                    ->type('nominal', $nominal * 1000)
                    ->pause(2000)
                    ->radio('type', $type[rand(0,1)])
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000);

            // check empty
            $browser->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(2) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(3) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(4) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(5) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(6) > div code span', 'This field required');

            // check unique
            $browser->select('provider_id')
                    ->pause(2000)
                    ->type('product_code', $random.$nominal)
                    ->pause(2000)
                    ->type('product_name', $random)
                    ->pause(2000)
                    ->type('nominal', $nominal * 1000)
                    ->pause(2000)
                    ->radio('type', $type[rand(0,1)])
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(3) > div code span', 'This code has already taken');
        });
    }

    public function testUpdateProduct()
    {
        $product = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $type = ['PULSA', 'DATA'];
        $nominal = rand(0,1000);
        $random = $product[rand(0,25)];

        $this->browse(function ($browser) use ($random, $nominal, $type){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/product')
                    ->pause(2000)
                    ->click('#producttabel tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(1)')
                    ->pause(2000);

            $browser->select('provider_id')
                    ->pause(2000)
                    ->type('product_code', $random.$nominal)
                    ->pause(2000)
                    ->type('product_name', $random)
                    ->pause(2000)
                    ->type('nominal', $nominal * 1000)
                    ->pause(2000)
                    ->radio('type', $type[rand(0,1)])
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(strtoupper($random.$nominal));
        });
    }

    public function testDeleteProduct()
    {

        $this->browse(function ($browser){
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->pause(2000)
                    ->visit('/product');

            $text = strtoupper($browser->pause(2000)->text('#producttabel tbody tr:nth-child(1) td:nth-child(3)'));

            $browser->pause(2000)
                    ->click('#producttabel tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(2)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSeeIn('#producttabel tbody tr:nth-child(1) td:nth-child(3)', $text);

        });
    }
}
