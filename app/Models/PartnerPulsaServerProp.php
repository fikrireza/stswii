<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPulsaServerProp extends Model
{
    protected $table = 'sw_partner_pulsa_server_properties';

    protected $primaryKey = 'partner_pulsa_id';

    public $timestamps = false;

    protected $fillable = ['partner_pulsa_id','partner_pulsa_id','server_url','api_key','api_secret','version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function partnerPulsa()
    {
      return $this->belongsTo('App\Models\PartnerPulsa', 'partner_pulsa_id');
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
