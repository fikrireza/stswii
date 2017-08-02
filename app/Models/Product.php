<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'sw_product';

    protected $primaryKey = 'product_id';

    protected $fillbale = ['product_id','product_code','product_name','provider_id','nominal','type_product','active','active_datetime','non_active_datetime','version','create_datetime','create_user_id','update_datetime','update_user_id'];

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
