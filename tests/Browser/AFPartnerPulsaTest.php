<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PartnerPulsaTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testReadPartnerPulsa()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Partner')
                    ->pause(2000)
                    ->clickLink('Partner Pulsa')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/partner-pulsa');
        });
    }

    public function testCreatePartnerPulsa()
    {
        $code = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $random = $code[rand(0,25)].'-'.rand(0,999);

        $faker = \Faker\Factory::create('id_ID');

        $this->browse(function ($browser) use ($random, $faker) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/partner-pulsa')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('partner_pulsa_code', $random)
                    ->pause(2000)
                    ->type('partner_pulsa_name', $faker->company)
                    ->pause(2000)
                    ->type('description', $faker->sentence(6, true))
                    ->pause(2000)
                    ->select('type_top', 'TERMIN')
                    ->pause(2000)
                    ->type('payment_termin', rand(1,20))
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(strtoupper($random));
        });
    }

    public function testValidationPartnerPulsa()
    {
        $code = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $random = $code[rand(0,25)].'-'.rand(0,999);

        $faker = \Faker\Factory::create('id_ID');

        $this->browse(function ($browser) use ($random, $faker) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/partner-pulsa')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000)
                    ->type('partner_pulsa_code', $random)
                    ->pause(2000)
                    ->type('partner_pulsa_name', $faker->company)
                    ->pause(2000)
                    ->type('description', $faker->sentence(6, true))
                    ->pause(2000)
                    ->select('type_top', 'TERMIN')
                    ->pause(2000)
                    ->type('payment_termin', rand(1,20))
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000);

            $browser->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(2) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(3) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(4) > div code span', 'This field required')
                    ->assertSeeIn('div:nth-child(5) > div code span', 'This field required');

            $browser->type('partner_pulsa_code', $random)
                    ->pause(2000)
                    ->type('partner_pulsa_name', $faker->company)
                    ->pause(2000)
                    ->type('description', $faker->sentence(6, true))
                    ->pause(2000)
                    ->select('type_top', 'TERMIN')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(2) > div code span', 'Partner Pulsa Code already exist')
                    ->assertSeeIn('div:nth-child(6) > div code span', 'This field required');

            $browser->type('partner_pulsa_code', '12345123451234512345123451234512345')
                    ->pause(2000)
                    ->select('type_top', 'DEPOSIT')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(2) > div code span', 'Maximum character 25');
        });
    }

    public function testUpdatePartnerPulsa()
    {
        // disarankan untuk mengosongkan table produt sell price supaya bisa jalan dengan baik
        $code = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oskar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $random = $code[rand(0,25)].'-'.rand(0,999);

        $faker = \Faker\Factory::create('id_ID');

        $this->browse(function ($browser) use ($random, $faker) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/partner-pulsa')
                    ->pause(2000)
                    ->click('#dataTables tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(1)')
                    ->pause(2000);

            $browser->type('partner_pulsa_code', $random)
                    ->pause(2000)
                    ->type('partner_pulsa_name', $faker->company)
                    ->pause(2000)
                    ->type('description', $faker->sentence(6, true))
                    ->pause(2000)
                    ->select('type_top', 'TERMIN')
                    ->pause(2000)
                    ->type('payment_termin', rand(1,20))
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(strtoupper($random));
        });
    }

    public function testDeletePartnerPulsa()
    {

        $this->browse(function ($browser) {
            $browser->loginAs(User::where('email', 'like', 'administrator%')->first())
                    ->visit('/partner-pulsa')
                    ->pause(2000);

            $text = strtoupper($browser->pause(2000)->text('#dataTables tbody tr:nth-child(1) td:nth-child(2)'));

            $browser->click('#dataTables tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(2)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSeeIn('#dataTables tbody tr:nth-child(1) td:nth-child(2)', $text);
        });
    }
}
