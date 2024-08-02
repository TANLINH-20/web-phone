<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'firstname', 'lastname', 'address', 'city', 'country', 'zip', 'telephone'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
