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
        $checkBalance = PartnerDepositBalance::where('balance_amount', '<=', 2500000)->get();

        $data = array([
                        'balanceAmount' => $checkBalance
                      ]);

        Mail::send('email.DepositNotification', ['data' => $data], function($message) use ($data) {
          $message->from('administrator@amadeo.id', 'Administrator')
                  ->to('fikrirezaa@gmail.com', 'Fikri Reza')
                  ->to('fourline66@gmail.com', 'Adam Surya')
                  ->subject('Deposit Notification');
        });

        $this->info('Agent List Deposit Has Been Sent to Email!');
    }

    // exceute cronjob with ->        * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
}
