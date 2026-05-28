<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
    ];

    /**
     * Get the threads for this category.
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class)->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc');
    }
}
