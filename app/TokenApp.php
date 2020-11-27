<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenApp extends Model
{
    protected $fillable = [
        'token','user_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
