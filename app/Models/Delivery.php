<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // Add other fillable fields here
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
