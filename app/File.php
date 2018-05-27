<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerUser;

class File extends Model
{
    protected $fillable = [
        'name', 'status','user_id'
    ];

    public function customerUser()
    {
        return $this->hasOne(CustomerUser::class);
    }

    public function user ()
    {
        return $this->BelongsTo(User::class);
    }


}
