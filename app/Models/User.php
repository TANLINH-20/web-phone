<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'address_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(ShoppingCart::class);
    }

    public function wishlistProducts()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
