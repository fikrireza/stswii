<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUniqueCode extends Model
{
    protected $table = 'api_uniquecode';

    protected $primaryKey = 'clientId';

    protected $fillable = ['clientId','name','uniqueCode','uniqueCodeDate'];
}
