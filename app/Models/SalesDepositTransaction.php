<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDepositTransaction extends Model
{
    protected $table = 'sw_sales_deposit_transaction';
    
    protected $primaryKey = 'sales_deposit_transaction_id';

    public $timestamps = false;

    protected $fillable = ['sales_deposit_transaction_id','sales_id','doc_no','doc_date', 'total_amount_deposit', 'status', 'create_datetime','create_user_id','update_datetime','update_user_id'];

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
