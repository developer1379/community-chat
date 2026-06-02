<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinRule extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'label',
        'description',
        'amount',
        'icon',
    ];
}
