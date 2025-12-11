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

    public function createTag(array $data):Tag
    {
        $existingTag = $this->tagRepo->findByName($data['name']);
        if($existingTag){
            throw new \Exception('Тег с таким названием уже существует');
        }
        return $this->tagRepo->createTag([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function deleteTagById(int $id):bool
    {
        return $this->tagRepo->deteleById($id);
    }
    public function update(int $id, array $data):Tag
    {
        $existingTag = $this->tagRepo->findByName($data['name']);
        if($existingTag && $existingTag->id != $id){
            throw new \Exception('Тег с таким названием уже существует');

        }
        $this->tagRepo->updateTagById($id, $data);
        return $this->tagRepo->getTagById($id);
    }
}