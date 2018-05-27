<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfc','nacionality','name', 'email', 'password', 'role', 'status', 'deleted','admin_id'
    ];

        public function Customers_users ()
    {
        return $this->hasmany(CustomerUser::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function customers() 
    {
        return $this->belongsToMany('App\Customer', 'customer_users', 'user_id', 'customer_id');
    }

    public function sourceMessages() 
    {
        return $this->belongsToMany('App\Message', 'source_id');
    }

    public function targetMessages() 
    {
        return $this->belongsToMany('App\Message', 'target_id');
    }

     public function banks() 
    {
        return $this->belongsToMany('App\Bank', 'customer_users', 'user_id', 'bank_id');
    }

    public function customerUser()
    {
        return $this->hasOne(CustomerUser::class);
    }

    public function scopeSearch($query, $search, $customer_id)
    {
        
        if ($search != '') {
           $query->where('name', 'like', "%$search%");
        }
        
        if ($customer_id != '') {
          /* $customers = Customer::where('name', 'like', "%$target%")->pluck('id');

           $userIds = CustomerUser::whereIn('customer_id', $customers)->pluck('user_id');

           return $query->whereIn('id', $userIds); */
           Log::info("Valor de customer_id: $customer_id");
           $userIds = CustomerUser::where('customer_id', $customer_id)->pluck('user_id');
           Log::info("Valor de customer_id: " . count($userIds));
           return $query->whereIn('id', $userIds);
        }
    }

    public function scopeEmployeeSearch($query, $search)
    {
        
        if ($search != '') {
            
           $query->where('name', 'like', "%$search%");
        }
        
    }

    public function getFullNameAttribute()
    {
        return 'freddy';
    }
}
