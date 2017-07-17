<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProductPurchPrice extends Model
{
    protected $table = 'amd_partner_product_purch_prices';

    protected $fillbale = ['parner_product_id','gross_purch_price','flg_tax','tax_percentage','datetime_start','datetime_end','active',
                          'active_datetime','non_active_datetime','version','create_user_id','update_user_id'];

    public function partnerpulsa()
    {
      return $this->belongsTo('App\Models\PartnerProduct', 'partner_product_id');
    }

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
