<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ['user_id', 'stock_id', 'quantity'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function stock()
    {
        return $this->belongsTo('App\Models\Stock');
    }
}
