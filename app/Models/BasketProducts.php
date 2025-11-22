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

    public function incrementCount(int $amount=1):void
    {
        $this->increment('count', $amount);
    }

    public function decrementCount(int $amount=1):bool
    {
         $newQuantity = $this->quantity - $amount;
        
        if ($newQuantity <= 0) {
            return $this->delete();
        }
        
        $this->decrement('quantity', $amount);
        return true;
    }

    public function updateCount(int $count): bool
    {
        if ($count <= 0) {
            return $this->delete();
        }
        
        return $this->update(['count' => $count]);
    }
    
    public function getTotalCostAttribute(): float
    {
        $price = $this->product->cost;
        $discount = $this->product->discount ?? 0;
        $finalPrice = $price * (1 - $discount / 100);
        
        return $finalPrice * $this->count;
    }
    

}
