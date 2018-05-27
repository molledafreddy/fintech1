<?php

namespace App;
use App\Credit;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = [
        'customer_user_id', 'amount',
    ];
    
    public function credit()
    {
        return $this->hasOne(Credit::class);
    }

    public function customer_users()
    {
        return $this->belongsTo('App\CustomerUser', 'customer_user_id');
    }
}

