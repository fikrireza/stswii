<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSellPrice extends Model
{
    protected $table = 'sw_product_sell_price';

    protected $primaryKey = 'product_sell_price_id';

    public $timestamps = false;

    protected $fillable = ['product_sell_price_id','product_id','gross_sell_price','flg_tax','tax_percentage','datetime_start','datetime_end','active','active_datetime','non_active_datetime','version','create_datetime','create_user_id','update_datetime','update_user_id'];

      public function product()
      {
        return $this->belongsTo('App\Models\Product', 'product_id');
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
