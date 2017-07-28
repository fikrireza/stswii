<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDepositBalance extends Model
{
    protected $table = 'sw_paloma_deposit_balance';

    protected $fillable = ['paloma_deposit_balance_id','partner_id','balance_amount','version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function partnerpulsa()
    {
        return $this->belongsTo('App\Models\PartnerPulsa', 'partner_id');
    }

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }

}
