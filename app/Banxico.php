<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banxico extends Model
{
    
    protected $fillable = [
        'number', 'city', 'status',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search != '') {
           $query->where('city', 'like', "%$search%");
        }
    }
}
