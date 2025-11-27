<?php

namespace App\Repositories;

use App\Models\BasketProducts;

class BasketRepository 
{
    public function __construct(private BasketProducts $basket)
    {

    }

    public function incrementCount(BasketProducts $basketProducts, int $amount =1):void
    {
        $basketProducts->increment('count', $amount);
    }

    public function decrementCount(BasketProducts $basketProducts, int $amount=1):bool
    {
        $newQuantity = $basketProducts->count - $amount;

        if($newQuantity <=0 ){
            return $basketProducts->delete();
        }
        $basketProducts->decrement('count', $amount);
        return true;
    }

    public function updateCount(BasketProducts $basketProducts, int $count):bool
    {
        if($count <=0){
            return $basketProducts->delete();
        }

        return $basketProducts->update(['count'=>$count]);
    }

    public function getTotalCost(BasketProducts $basketProducts):float
    {
        $price = $basketProducts->product->cost;
        $discount = $basketProducts->product->discount ?? 0;
        $finalPrice = $price * (1 - $discount / 100);
        return $finalPrice * $basketProducts->count;
    }

}