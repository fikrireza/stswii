<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'sw_agent';
    
    protected $primaryKey = 'agent_id';

    public $timestamps = false;

    protected $fillable = ['agent_id','agent_name','phone_number','address','city','channel_user_id','channel_chat_id','paloma_member_code, client_id','version','create_datetime','create_user_id','update_datetime','update_user_id'];

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
