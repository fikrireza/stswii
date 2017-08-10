<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalomaDepositTrx extends Model
{
    protected $table = 'sw_paloma_deposit_trx';

    protected $primaryKey = 'paloma_deposit_trx_id';

    public $timestamps = false;

    protected $fillable = ['paloma_deposit_trx_id','tenant_id','ou_id','doc_type_id','doc_no','doc_date','partner_code','deposit_amount','status','confirmed_user_id','confirmed_datetime','version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
