<?php

namespace Ovarun\HmacAuth\Models;

use Illuminate\Database\Eloquent\Model;

class HmacClient extends Model
{
    protected $fillable = ['client_id', 'name', 'secret', 'active'];
    protected $hidden = ['secret'];
}