<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricalImport extends Model
{
    protected $fillable = [
        'name','ip_address','user_id'
    ];
}
