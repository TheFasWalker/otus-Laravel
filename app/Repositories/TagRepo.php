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
    
    public function getTagById(int $id)
    {
        return Tag::findOrFail($id);
    }

    public function createTag(array $data):Tag
    {
        return Tag::create($data);
    }

    public function findByName(string $name): ?Tag
    {
        return Tag::where('name', $name)->first();
    }

    public function deteleById(int $id):bool
    {
        $tag= Tag::findOrFail($id);
        return $tag->delete();
    }
}