<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class TagRepository
{
    public function __construct(private Tag $tag)
    {

    }

    public function findProductsByTag(Builder $query, string $name): Builder
    {
        return $query->where('name','like', "%{name}");

    }
}