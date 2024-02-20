<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cat_id', 'price', 'img'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function extensions()
    {
        return $this->hasMany(Extension::class, 'product_id');
    }
}
