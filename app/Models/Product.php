<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable =[
        'name',
        'description',
        'preview',
        'country_id',
        'user_id'
    ];

    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'product_tag');
    }
    public function author():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
