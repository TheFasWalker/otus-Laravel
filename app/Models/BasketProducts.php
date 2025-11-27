<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasketProducts extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'count'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

}
