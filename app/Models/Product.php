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
        'discout',
        'cost',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
