<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    protected $table = 'wl_balance_log';

    protected $primaryKey = 'wallet_balance_log_id';

    public $timestamps = false;

    protected $fillable = ['wallet_balance_log_id','balance_id','biller_id', 'acquirer_id', 'unique_code', 'unique_code_date', 'ref_doc_type_id', 'ref_datetime', 'ref_doc_no', 'amount', 'remark', 'flag_reversal', 'version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }


}
