<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    
    protected $fillable = [
        'name', 'address','phone', 'web_site', 'rfc', 'city',
        'cp_first_name', 'cp_last_name', 'cp_email', 'cp_phone', 'active','comments','status','admin_id'
    ];

    public function Customers_users()
	{
	 	return $this->hasmany(CustomerUser::class);
	}
    public function users()
    {
    	return $this->belongsToMany('App\User', 'customer_users', 'customer_id', 'user_id');
    }

    public function scopeSearch($query,$search,$status)
    {
        if ($search != '') {
           $query->where('name', 'like', "%$search%");
        }
        
        if ($status != '') {
           $query->where('status', $status);
        }
    }
}
