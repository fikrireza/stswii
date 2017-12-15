<?php
namespace App\Console\Commands;
/**
 * @author Setiadi Nov 20, 2017
 */
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateCsvDataSalesMlm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:csv-data-sales-mlm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CSV Data Sales MLM';

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
    	$yesterday = date('Ymd',strtotime("-1 days"));

        $data = DB::select("select a.pos_id, b.paloma_member_code, d.product_code, c.member_price, c.pv, a.receiver_phone_number, 
            a.create_datetime, a.create_user_id, a.update_datetime, a.update_user_id, a.version, a.status,
            a.purchase_datetime
            from sw_pos as a
            inner join sw_agent as b on b.agent_id = a.agent_id
            inner join sw_product_sell_price_mlm as c on c.product_id = a.product_id and a.purchase_datetime between c.datetime_start and datetime_end
            inner join sw_product as d on d.product_id = c.product_id
            where left(a.purchase_datetime, 8) = '$yesterday' and b.paloma_member_code != ''");

        $file = fopen(env('DIR_SALES_PULSA_MLM').date('Y-M-d-His', strtotime("-1 days")).".csv", "ar");

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);

        foreach ($data as $line){
            fputcsv($file, $line, ";");
        }

        fclose($file);
        
        $this->info("Generate CSV Data Sales MLM ".date('Y-M-d-His',strtotime("-1 days")));
    }

    // exceute cronjob with ->        * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
}
