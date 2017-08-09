<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProduct extends Model
{
    protected $table = 'sw_partner_product';

    protected $primaryKey = 'partner_product_id';

    public $timestamps = false;

    protected $fillable = ['partner_product_id','partner_pulsa_id','provider_id','product_id','partner_product_code','partner_product_name','active','active_datetime','non_active_datetime','version','create_datetime','create_user_id','update_datetime','update_user_id'];


    public function partnerpulsa()
    {
      return $this->belongsTo('App\Models\PartnerPulsa', 'partner_pulsa_id');
    }

    public function provider()
    {
      return $this->belongsTo('App\Models\Provider', 'provider_id');
    }

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
