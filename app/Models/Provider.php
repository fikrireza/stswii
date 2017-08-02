<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'sw_provider';

    protected $fillable = ['provider_id','provider_code','provider_name','version','create_datetime',
                          'create_user_id','update_datetime','update_user_id'];

    protected $primaryKey = 'provider_id'; // or null

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }


}
