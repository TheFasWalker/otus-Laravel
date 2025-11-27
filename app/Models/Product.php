<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [            
        'title',
        'article',
        'description',
        'preview',
        'count',
        'discount',
        'cost',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function attachTags(array $tagIds): void
    {
        $this->tags()->syncWithoutDetaching($tagIds);
    }

    public function detachTags(array $tagIds = []): void
    {
        if (empty($tagIds)) {
            $this->tags()->detach();
        } else {
            $this->tags()->detach($tagIds);
        }
    }

    public function updateTags(array $tagIds)
    {
        $this->tags()->sync($tagIds);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
