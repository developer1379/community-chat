<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankMilestone extends Model
{
    protected $primaryKey = 'level';
    public $incrementing = false;

    protected $fillable = [
        'level',
        'name',
        'coins_required',
        'icon',
        'color',
        'badge',
    ];
}
