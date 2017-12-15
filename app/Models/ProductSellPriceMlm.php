<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSellPriceMlm extends Model
{
    protected $table = 'sw_product_sell_price_mlm';

    protected $primaryKey = 'product_sell_price_mlm_id';

    public $timestamps = false;

    protected $fillable = ['product_sell_price_mlm_id','product_id','catalog_price','disc_member_percent','disc_promo_percent', 'member_price', 'fee_ds_amount', 'performance_bonus_percent', 'pv', 'datetime_start', 'datetime_end', 'datetime_start','datetime_end','active','active_datetime','non_active_datetime','version','create_datetime','create_user_id','update_datetime','update_user_id'];

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
