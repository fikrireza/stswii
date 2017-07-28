<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPulsaServerProp extends Model
{
    protected $table = 'sw_partner_pulsa_server_properties';

    protected $fillable = ['server_url','api_key','api_secret','version','create_user_id','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
