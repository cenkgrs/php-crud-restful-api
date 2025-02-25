<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use DateTime;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::insert([
            'order_id' => 1,
            'product_id' => 102,
            'created_at' => new DateTime
        ]);

        OrderItem::insert([
            'order_id' => 2,
            'product_id' => 101,
            'created_at' => new DateTime
        ]);

        OrderItem::insert([
            'order_id' => 2,
            'product_id' => 100,
            'created_at' => new DateTime
        ]);


        OrderItem::insert([
            'order_id' => 3,
            'product_id' => 102,
            'created_at' => new DateTime
        ]);

        OrderItem::insert([
            'order_id' => 3,
            'product_id' => 100,
            'created_at' => new DateTime
        ]);
    }
}
