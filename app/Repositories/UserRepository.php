<?php

namespace App\Repositories;

use App\Models\BasketProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function __construct(private User $user)
    {

    }

    public function addToBasket(User $user, int $productId, int $count=1):?BasketProducts
    {
        if (!Product::where('id', $productId)->exists()) {
            return null;
        }

        $basketItem = $user->basketProducts()
            ->where('product_id', $productId)
            ->first();
        if ($basketItem) {
            $basketItem->increment('count', $count);
            return $basketItem->fresh();
        }
        return $user->basketProducts()->create([
            'product_id' => $productId,
            'count' => max(1, $count),
        ]);
    }

    public function decrementBasketItem(User $user, int $productId, int $amount =1):bool
    {
        $basketItem = $user->basketProducts()
        ->where('product_id', $productId)
        ->first();

        if($basketItem){
            return false;
        }

        $newQuantity = $basketItem->count - $amount;

        if($newQuantity <=0){
            return $basketItem->delete();
        }

        $basketItem->decrement('count', $amount);
        return true;
    }
    public function removeFromBasket(User $user, int $productId):bool{
        return $user->basketProducts()
        ->where('product_id' , $productId)
        ->delete();
    }

    public function clearBasket(User $user):bool
    {
        return $user->basketProducts()->delete();
    }

}