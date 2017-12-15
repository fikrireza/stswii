<?php
namespace App\Console\Commands;
/**
 * @author Efendi Oct 17, 2017
 */
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivatedSalesman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activated:salesman';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activated Salesman';

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
    	
    	$updateData = [
    			'activeNo'=>'N',
    			'activeYes'=>'Y',
    			'update_datetime'=>$datetimeNow,
    			'non_active_datetime'=>'',
    			'update_user_id'=>-99
    	];
        
        $result = DB::update("UPDATE sw_sales SET active = :activeYes, update_datetime = :update_datetime,
        		non_active_datetime = :non_active_datetime, update_user_id = :update_user_id, version=version+1
        		WHERE active = :activeNo ",$updateData);
        
        $this->info("$result Salesman has been activated");
    }

    // exceute cronjob with ->        * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
}
