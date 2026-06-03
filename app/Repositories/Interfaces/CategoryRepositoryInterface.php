<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAllWithStats(): Collection;
    public function findBySlug(string $slug): ?Category;
}
