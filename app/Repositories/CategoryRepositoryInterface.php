<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAllWithStats(): Collection;
    public function findBySlug(string $slug): ?Category;
}
