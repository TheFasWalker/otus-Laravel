<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function role(){
        return $this->belongsTo(Role::class);
    }
    
    public function isAdmin():bool{
        return $this->role_id==1;
    }

    public function basketProducts()
    {
        return $this->hasMany(BasketProducts::class);
    }

    public function addToBasket(int $productId, int $count = 1)
    {
        if (!Product::where('id', $productId)->exists()) {
            return null;
        }

        $basketItem = $this->basketProducts()
            ->where('product_id', $productId)
            ->first();

        if ($basketItem) {
            $basketItem->incrementCount($count);
            return $basketItem->fresh();
        }

        return $this->basketProducts()->create([
            'product_id' => $productId,
            'count' => max(1, $count),
        ]);
    }

    public function decrementBasketItem(int $productId, int $amount = 1): bool
    {
        $basketItem = $this->basketProducts()
            ->where('product_id', $productId)
            ->first();

        if (!$basketItem) {
            return false;
        }

        return $basketItem->decrementCount($amount);
    }

    public function removeFromBasket(int $productId): bool
    {
        return $this->basketProducts()
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearBasket(): void
    {
        $this->basketProducts()->delete();
    }
}
