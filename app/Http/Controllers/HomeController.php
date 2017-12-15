<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Provider;
use App\Models\ProviderPrefix;
use App\Models\Product;
use App\Models\PartnerPulsa;
use App\Models\PartnerProduct;
use App\Models\Agent;

use Auth;
use Carbon\Carbon;
use DB;
use Validator;

class HomeController extends Controller
{

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
				$this->middleware('auth');
		}

		public function index(Request $request)
		{
			
			$message = [
					'f_month.string' => 'Invalid filter',
					'f_year.integer'=>'Invalid filter',
			];
			
			$validator = Validator::make($request->all(), [
					'f_month' => 'string|nullable',
					'f_year' => 'integer|nullable',
			], $message);
			
			if($validator->fails())
			{
				return redirect()->route('home.index');
			}
			
			$provider = Provider::count();
			$providerPrefix = ProviderPrefix::count();
			$product = Product::count();
			$partnerPulsa = PartnerPulsa::count();
			$partnerProduct = PartnerProduct::count();
			$agent = Agent::where('paloma_member_code','')->count();
			$agentMlm = Agent::where('paloma_member_code','<>','')->count();
			
			$queryString = "SELECT array_to_json(array_agg(row_to_json(A))) AS data FROM (
				SELECT provider_name AS name, 'column' AS \"type\",
				(
					SELECT array_to_json(array_agg(row_to_json(B))) FROM (
						SELECT CAST(TO_CHAR(TO_DATE(D.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint) AS \"x\", SUM(D.gross_sell_price) AS \"y\"
						FROM sw_pos D
						INNER JOIN sw_product E ON D.product_id = E.product_id AND E.provider_id = C.provider_id
						WHERE LEFT(D.purchase_datetime,6) = :monthNow
						AND D.status = 'S'
						GROUP BY CAST(TO_CHAR(TO_DATE(D.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint), C.provider_id
						ORDER BY CAST(TO_CHAR(TO_DATE(D.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint) ASC
					) B
				)
				AS data
				FROM sw_provider C
				UNION ALL
				SELECT 'Sales '||:oneMonthAgoName AS name, 'spline' AS \"type\",(
					SELECT array_to_json(array_agg(row_to_json(G))) FROM (
						SELECT CAST(TO_CHAR(TO_DATE(H.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint) AS \"x\", SUM(H.gross_sell_price) AS \"y\"
						FROM sw_pos H
						WHERE LEFT(H.purchase_datetime,6) = :oneMonthAgo
						AND H.status = 'S'
						GROUP BY CAST(TO_CHAR(TO_DATE(H.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint)
						ORDER BY CAST(TO_CHAR(TO_DATE(H.purchase_datetime,'YYYYMMDDHH24MISS'),'DD') AS bigint) ASC
					) G
				) AS data
			) A;";
			
			$yearMonthNow = Carbon::now()->format('Ym');
			if(isset($request->f_month) && $request->f_month != '' && isset($request->f_year) && $request->f_year != '')
			{
				$yearMonthNow = $request->f_year.$request->f_month;
			}else{
				$request->f_year = Carbon::now()->format('Y');
				$request->f_month = Carbon::now()->format('m');
			}
			
			$oneMonthAgo = Carbon::createFromFormat('Ym',$yearMonthNow)->subMonth()->format('Ym');
			$oneMonthAgoName = Carbon::createFromFormat('Ym',$oneMonthAgo)->format('F');
			$thisMonthYearName = Carbon::createFromFormat('Ym',$yearMonthNow)->format('F Y');
			
			$params = [
					'monthNow'=>$yearMonthNow,
					'oneMonthAgo'=> $oneMonthAgo,
					'oneMonthAgoName'=>$oneMonthAgoName
			];
			
			$query = DB::select($queryString,$params);
			$dataChartSalesMonthly = $query[0]->data;
			//$dataChartSalesMonthly = '[{"name":"XL","type":"column","data":[{"x":1,"y":48},{"x":2,"y":70},{"x":3,"y":99},{"x":4,"y":110},{"x":5,"y":114},{"x":6,"y":116},{"x":7,"y":131},{"x":8,"y":141},{"x":9,"y":211},{"x":10,"y":114},{"x":11,"y":91},{"x":12,"y":51}]},{"name":"Simpati","type":"column","data":[{"x":1,"y":49},{"x":2,"y":71},{"x":3,"y":106},{"x":4,"y":129},{"x":5,"y":144},{"x":6,"y":176},{"x":7,"y":135},{"x":8,"y":148},{"x":9,"y":216},{"x":10,"y":194},{"x":11,"y":95},{"x":12,"y":54}]},{"name":"Sales September","type":"spline","data":[{"x":1,"y":70},{"x":2,"y":69},{"x":3,"y":50},{"x":4,"y":14},{"x":5,"y":40},{"x":6,"y":29},{"x":7,"y":25},{"x":8,"y":26},{"x":9,"y":33},{"x":10,"y":83},{"x":11,"y":39}]}]';
			
			$queryStringDeposit = "SELECT array_to_json(array_agg(row_to_json(A))) AS data FROM (
										SELECT 'MLM' AS name, 'column' AS \"type\",
										(
											SELECT array_to_json(array_agg(row_to_json(B))) FROM (
												SELECT SUM(D.balance_amount) AS \"y\",
												COUNT(1) AS \"jumlah\"
												FROM wl_balance D
												INNER JOIN sw_agent E ON E.client_id = CAST(D.client_id AS character varying)
												AND D.balance_amount > 0 AND E.paloma_member_code <> ''
												UNION
												SELECT 0 AS \"y\",
												0 AS \"jumlah\"
												ORDER BY y DESC
											) B
										) AS data
										UNION ALL
										SELECT 'Tradisional' AS name, 'column' AS \"type\",
										(
											SELECT array_to_json(array_agg(row_to_json(B))) FROM (
												SELECT SUM(D.balance_amount) AS \"y\",
												COUNT(1) AS \"jumlah\"
												FROM wl_balance D
												INNER JOIN sw_agent E ON E.client_id = CAST(D.client_id AS character varying)
												AND D.balance_amount > 0 AND E.paloma_member_code = ''
												UNION
												SELECT 0 AS \"y\",
												0 AS \"jumlah\"
												ORDER BY y DESC
											) B
										) AS data
										UNION ALL
										SELECT C.partner_pulsa_name AS name, 'column' AS \"type\",
										(
											SELECT array_to_json(array_agg(row_to_json(B))) FROM (
												SELECT balance_amount AS \"y\"
												FROM sw_paloma_deposit_balance D
												WHERE D.partner_pulsa_id = C.partner_pulsa_id
												UNION
												SELECT 0 AS \"y\"
												ORDER BY y ASC
											) B
										) AS data
										FROM sw_partner_pulsa C
										WHERE C.type_top = 'DEPOSIT'
									) A;";
			
			$dataChartDeposit = DB::select($queryStringDeposit)[0]->data;
			
			
			return view('home.index', compact('provider','providerPrefix','product','partnerPulsa','partnerProduct','agent','agentMlm','dataChartSalesMonthly','thisMonthYearName','dataChartDeposit','request'));
			
		}
}
