<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPulsa extends Model
{
    protected $table = 'sw_partner_pulsa';

    protected $fillable = ['parner_pulsa_code','description','partner_pulsa_name','flg_need_deposit','payment_termin',
                          'active','active_datetime','non_active_datetime','version','create_user_id','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
