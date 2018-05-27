<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerUser;
use App\User;

class Bank extends Model
{
    //
     protected $fillable = [
        'name','number','transfer_key'
    ];

    public function Customers_users()
	{
	 	return $this->hasmany(CustomerUser::class);
	}
    public function users()
    {
    	return $this->belongsToMany('App\User', 'customer_users', 'bank_id', 'user_id');
    }

    public function scopeSearch($query, $search)
    {
        if ($search != '') {
           $query->where('name', 'like', "%$search%");
        }
    }
}
