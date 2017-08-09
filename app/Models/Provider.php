<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'sw_provider';

    protected $primaryKey = 'provider_id';

    public $timestamps = false;

    protected $fillable = ['provider_id','provider_code','provider_name','version','create_datetime','create_user_id','update_datetime','update_user_id'];


    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }


}
