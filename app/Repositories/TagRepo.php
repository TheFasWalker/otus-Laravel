<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagRepo
{
    public function getAllTags(): Collection
    {
        return Tag::all();
    }
}