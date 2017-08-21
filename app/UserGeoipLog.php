<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGeoipLog extends Model
{

    protected $table = 'user_geoip_logs';
    protected $fillable = ['user_id', 'country', 'login_date'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}