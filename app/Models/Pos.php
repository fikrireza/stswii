<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    protected $table = 'sw_pos';

    protected $fillable = ['pos_id','doc_type_id','doc_no','purchase_datetime','agent_id','product_id','partner_product_id','receiver_phone_number','gross_sell_price','sell_flg_tax_ammount','sell_tax_percentage','gross_purch_price','purch_flg_tax_ammount','purch_tax_percentage','status','status_remark','version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }
}
