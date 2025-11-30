<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepo;
use Illuminate\Database\Eloquent\Collection;

class TagsServece
{
    public function  __construct(private TagRepo $tagRepo)
    {
     
    }

    public function getAllTags(): Collection
    {
        return $this->tagRepo->getAllTags();
    }

    public function getTagById(int $id)
    {
        return $this->tagRepo->getTagById($id);
    }
}