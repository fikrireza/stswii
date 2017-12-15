<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDepositTransactionDetail extends Model
{
    protected $table = 'sw_sales_deposit_transaction_detail';
    
    protected $primaryKey = 'sales_deposit_transaction_detail_id';

    public $timestamps = false;

    protected $fillable = ['sales_deposit_transaction_detail_id','sales_deposit_transaction_id','line_no','agent_id', 'phone_number', 'amount_deposit', 'create_datetime','create_user_id','update_datetime','update_user_id', 'version'];

    public $incrementing = false;

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
