<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProduct extends Model
{
    protected $table = 'amd_partner_products';

    protected $fillable = ['partner_pulsa_id','provider_id','partner_product_code','partner_product_name','active',
                          'active_datetime','non_active_datetime','version','create_user_id','update_user_id'];

    public function partnerpulsa()
    {
      return $this->belongsTo('App\Models\PartnerPulsa', 'partner_pulsa_id');
    }

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
