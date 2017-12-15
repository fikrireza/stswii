<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sw_sales';
    
    protected $primaryKey = 'sales_id';

    public $timestamps = false;

    protected $fillable = ['sales_id','user_id','limit_deposit','version','create_datetime','create_user_id','update_datetime','update_user_id', 'active', 'active_datetime', 'non_active_datetime'];

    public $incrementing = false;

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
