<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AEProductSellPriceTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testReadProductSellPrice()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::first())
                    ->visit('/home')
                    ->pause(2000)
                    ->clickLink('Manage Product')
                    ->pause(2000)
                    ->click('.menu_section>ul>li:nth-child(3)>ul>li:nth-child(2)>a')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/product-sell-price');
        });
    }

    public function testReadUploadProductSellPrice()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::first())
                    ->visit('/product-sell-price')
                    ->pause(2000)
                    ->clickLink('Upload')
                    ->pause(2000)
                    ->assertPathIs('/stswii/public/product-sell-price/upload');
        });
    }

    public function testCreateProductSellPrice()
    {
        // disarankan untuk mengosongkan table produt sell price supaya bisa jalan dengan baik
        $nominal = rand(1,999) * 1000;
        $start_date = date('Y-m-d H:i:s', strtotime("+".rand(1,15)." days +".rand(1,3)." months +".rand(0,1)." years"));
        $end_date = date('Y-m-d H:i:s', strtotime("+".rand(16,30)." days +".rand(4,6)." months +".rand(2,3)." years"));

        $this->browse(function ($browser) use ($nominal, $start_date, $end_date){
            $browser->loginAs(User::first())
                    ->visit('/product-sell-price')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000);

            $browser->select('product_id')
                    ->pause(2000)
                    ->type('gross_sell_price', $nominal)
                    ->pause(2000)
                    ->check('flg_tax')
                    ->pause(2000)
                    ->type('tax_percentage', rand(1,20))
                    ->pause(2000)
                    ->type('datetime_start', $start_date)
                    ->pause(2000)
                    ->type('datetime_end', $end_date)
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(date('d-m-Y H:i:s', strtotime($start_date)));
        });
    }

    public function testValidationProductSellPrice()
    {
        // disarankan untuk mengosongkan table produt sell price supaya bisa jalan dengan baik
        $nominal = rand(1,999) * 1000;
        $start_date = date('Y-m-d H:i:s', strtotime("+".rand(1,15)." days +".rand(1,3)." months +".rand(0,1)." years"));
        $end_date = date('Y-m-d H:i:s', strtotime("+".rand(16,30)." days +".rand(4,6)." months +".rand(2,3)." years"));

        $this->browse(function ($browser) use ($nominal, $start_date, $end_date){
            $browser->loginAs(User::first())
                    ->visit('/product-sell-price')
                    ->pause(2000)
                    ->clickLink('Add')
                    ->pause(2000);

            $browser->select('product_id')
                    ->pause(2000)
                    ->type('gross_sell_price', $nominal)
                    ->pause(2000)
                    ->check('flg_tax')
                    ->pause(2000)
                    ->type('tax_percentage', rand(1,20))
                    ->pause(2000)
                    ->type('datetime_start', $start_date)
                    ->pause(2000)
                    ->type('datetime_end', $end_date);

            $product_id = $browser->value('select[name=product_id]');

            $browser->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(date('d-m-Y H:i:s', strtotime($start_date)));

            $browser->clickLink('Add')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(2) > div code span', 'This field is required.')
                    ->assertSeeIn('div:nth-child(3) > div code span', 'This field is required.');

            $browser->select('product_id', $product_id)
                    ->pause(2000)
                    ->type('gross_sell_price', $nominal)
                    ->pause(2000)
                    ->check('flg_tax')
                    ->pause(2000)
                    ->type('datetime_start', $end_date)
                    ->pause(2000)
                    ->type('datetime_end', $start_date)
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSeeIn('div:nth-child(5) > div code span', 'This field is required.')
                    ->assertSeeIn('div:nth-child(6) > div code span', 'Higher Than Datetime End.');

            $browser->select('product_id', $product_id)
                    ->pause(2000)
                    ->uncheck('flg_tax')
                    ->pause(2000)
                    ->type('datetime_start', $start_date)
                    ->pause(2000)
                    ->type('datetime_end', $end_date)
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee('Data is still active.');
        });
    }

    public function testUpdateProductSellPrice()
    {
        // disarankan untuk mengosongkan table produt sell price supaya bisa jalan dengan baik
        $nominal = rand(1,999) * 1000;
        $start_date = date('Y-m-d H:i:s', strtotime("+".rand(1,15)." days +".rand(1,3)." months +".rand(0,1)." years"));
        $end_date = date('Y-m-d H:i:s', strtotime("+".rand(16,30)." days +".rand(4,6)." months +".rand(2,3)." years"));

        $this->browse(function ($browser) use ($nominal, $start_date, $end_date){
            $browser->loginAs(User::first())
                    ->visit('/product-sell-price')
                    ->pause(2000)
                    ->click('#producttabel_wrapper tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(1)')
                    ->pause(2000);

            $browser->select('product_id')
                    ->pause(2000)
                    ->type('gross_sell_price', $nominal)
                    ->pause(2000)
                    ->type('datetime_start', $start_date)
                    ->pause(2000)
                    ->type('datetime_end', $end_date)
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->click('label[for=active]')
                    ->pause(2000)
                    ->press('Submit')
                    ->pause(2000)
                    ->assertSee(date('d-m-Y H:i:s', strtotime($start_date)));
        });
    }

    public function testDeleteProductSellPrice()
    {

        $this->browse(function ($browser){
            $browser->loginAs(User::first())
                    ->visit('/product-sell-price');

            $text = strtoupper($browser->pause(2000)->text('#producttabel_wrapper tbody tr:nth-child(1) td:nth-child(2)'));

            $browser->pause(2000)
                    ->click('#producttabel_wrapper tbody tr:nth-child(1) td:nth-last-child(1) a:nth-child(2)')
                    ->pause(2000)
                    ->click('#setDelete')
                    ->pause(2000)
                    ->assertDontSeeIn('#producttabel_wrapper tbody tr:nth-child(1) td:nth-child(2)', $text);

        });
    }
}
