<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderPrefix extends Model
{
    protected $table = 'sw_provider_prefix';

    protected $fillable = ['provider_prefix_id','provider_id','prefix','version','create_datetime',
                          'create_user_id','update_datetime','update_user_id'];

    protected $primaryKey = 'provider_prefix_id'; // or null

    public function provider()
    {
      return $this->belongsTo('App\Models\Provider', 'provider_id');
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
