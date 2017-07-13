<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderPrefix extends Model
{
    protected $table = 'amd_provider_prefixes';

    protected $fillable = ['provider_id','prefix','version','create_user_id','update_user_id'];

    public function provider()
    {
      return $this->belongsTo('App\Models\Provider', 'provider_id');
    }

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'created_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
