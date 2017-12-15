<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMlm extends Model
{
    protected $table = 'sw_product_mlm';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillbale = ['product_id','version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
