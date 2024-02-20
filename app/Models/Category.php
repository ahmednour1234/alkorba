<?php

// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Add any additional relationships, attributes, or methods as needed
    public function products()
    {
        return $this->hasMany(Product::class, 'cat_id');
    }
    public function coffees()
    {
        return $this->hasMany(Coffee::class, 'cat_id');
    }
}
