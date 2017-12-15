<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PartnerDepositBalance;
use App\Models\User;

use Mail;

class DepositNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Notification Deposit Paloma';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $checkBalance = PartnerDepositBalance::join('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_paloma_deposit_balance.partner_pulsa_id')
											->select('sw_partner_pulsa.partner_pulsa_code', 'sw_partner_pulsa.partner_pulsa_name', 'sw_paloma_deposit_balance.balance_amount')
											->where('sw_paloma_deposit_balance.balance_amount', '<=', 5000000)
											->get();

        if(count($checkBalance) == 0) return;

        $data = array([
                        'balanceAmount' => $checkBalance
                      ]);

        Mail::send('email.depositNotification', ['data' => $data], function($message) use ($data) {
            $recipients = ['beny@solusiteknologi.co.id', 'hells_my@yahoo.co.id'];
          $message->to($recipients)
                  ->subject('Deposit Notification');
        });

        $this->info('Agent List Deposit Has Been Sent to Email!');
    }

    // exceute cronjob with ->        * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
}
