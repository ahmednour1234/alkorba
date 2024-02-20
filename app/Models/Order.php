<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','delivery_id','statu_id', 'address_id', 'phone', 'note', 'total_price'];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function deliveries()
    {
        return $this->belongsTo(Delivery::class);
    }
    public function statuses()
    {
        return $this->belongsTo(Status::class);
    }
    public function addresses()
    {
        return $this->belongsTo(Address::class);
    }
}
