<?php

namespace App;
use App\Bank;
use App\Credit;
use App\Account;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'number','status','daily_amount','bank_id',
    ];

    public function bank()
    {
        return $this->BelongsTo(Bank::class);
    }
    
        public function getFullNameAttribute(){
            return 'freddy molleda';
        }

    public function getAvailableAttribute($account_id)
    {
        
    }
    
}
