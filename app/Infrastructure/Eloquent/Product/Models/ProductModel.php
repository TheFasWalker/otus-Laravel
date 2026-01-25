<?php

namespace App\Infrastructure\Eloquent\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductModel extends Model
{
    protected $table = 'products';
    
    protected $fillable = [
        'name',
        'description',
        'preview',
        'country_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Infrastructure\Eloquent\Tag\Models\TagModel::class,
            'product_tag',
            'product_id',
            'tag_id'
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(
            \App\Infrastructure\Eloquent\Country\Models\CountryModel::class,
            'country_id'
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(
            \App\Infrastructure\Eloquent\User\Models\UserModel::class,
            'user_id'
        );
    }
}