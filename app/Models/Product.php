<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'photo', 'brand', 'name', 'description',
        'details', 'price', // removed stray single quote
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    } // added missing closing bracket

    public function reviews()
    {
        return $this->hasMany(Review::class);
    } // added missing closing bracket

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    } // added missing closing bracket
}
