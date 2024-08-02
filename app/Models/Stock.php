<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id', 'size', 'color', 'quantity'];
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
