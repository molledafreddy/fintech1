<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{

  protected $fillable = [
        'order_id', 'status','account_id'
    ];
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function scopeSearch($query, $search)
    {
        
        if ($search != '') {
           $query->where('status', 'like', "%$search%");
        }
        
    }

}
