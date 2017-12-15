<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'sw_notification';

    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    protected $fillable = ['notification_id','agent_id','title', 'message', 'status', 'meta', 'version','create_datetime','create_user_id','update_datetime','update_user_id'];

    public function createdBy()
    {
      return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function updatedBy()
    {
      return $this->belongsTo('App\Models\User', 'update_user_id');
    }


}
