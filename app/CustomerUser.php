<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
use App\Customer;
use App\User;
use App\Bank;
use App\File;
use Log;

class CustomerUser extends Model
{
    protected $fillable = [
        'id','customer_id', 'user_id', 'bank_id','phone','number','biweekly_salary','acconunt_number','acconunt_clabe','status','file_name','acceptance_terms','file_id'
    ];

    
    public function orders ()
    {
        return $this->HasMany(Order::class);
    }

    public function customer ()
    {
        return $this->BelongsTo(Customer::class);
    }

    public function user ()
    {
        return $this->BelongsTo(User::class);
    }

    public function file ()
    {
        return $this->BelongsTo(File::class);
    }

    public function bank ()
    {
        return $this->BelongsTo(Bank::class);
    }

    public function scopeStatus()
    {
        $orders = $this->orders;
        return $orders;
    }

    public function scopeSearch($query, $search,$customer_id)
    {
        
        if ($search != '') {
           $query->where('acconunt_number', 'like', "%$search%")
                  ->orWhere('acconunt_clabe', 'like', "%$search%")   
                 ;
        }
        
        if ($customer_id != '') {
           Log::info("Valor de customer_id: $customer_id");
           $userIds = CustomerUser::where('customer_id', $customer_id)->pluck('user_id');
           Log::info("Valor de customer_id: " . count($userIds));
           return $query->whereIn('customer_id', $userIds);
        }
    }

    public function scopeFile($query, $file)
    {
        if ($file != '') {
            return $query->where('file_id', $file);
        }
    }


}

