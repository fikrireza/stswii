<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDepositBalance extends Model
{
    protected $table = 'sw_partner_deposit_balances';

    protected $fillable = ['partner_id','balance_amount','version','create_user_id','update_user_id'];

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
