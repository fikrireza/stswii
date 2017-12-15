<?php
namespace App\Console\Commands;
/**
 * @author Efendi Nov 1, 2017
 */
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoidUniqueCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'void:uniqueCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Void Unique Code';

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
    	$datetimeNow = Carbon::now()->format('YmdHis');
    	$date3DaysAgo = Carbon::now()->subDays(3)->format('Ymd');
    	
    	$updateData = [
    			'flgConfirmNo'=>'N',
    			'flgConfirmVoid'=>'V',
    			'expiredDate'=>$date3DaysAgo,
    			'update_datetime'=>$datetimeNow,
    			'update_user_id'=>-99
    	];
        
        $result = DB::update("UPDATE wl_unique_codes SET flg_confirmed = :flgConfirmVoid, update_datetime = :update_datetime,
        		update_user_id = :update_user_id, version=version+1
        		WHERE flg_confirmed = :flgConfirmNo AND unique_code_date <= :expiredDate ",$updateData);
        
        $this->info("$result Unique Code has been void");
    }

    // exceute cronjob with ->        * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
}
