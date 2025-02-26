<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orderİtem;

class Order extends Model
{
    public function order_items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
