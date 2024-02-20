<?php

// app/Models/OrderDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'product_id', 'data', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
