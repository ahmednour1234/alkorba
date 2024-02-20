<?php

// app/Models/Coffee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coffee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cat_id', 'price', 'img'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
}
