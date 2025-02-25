<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use DateTime;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::insert([
            'customer_id' => 1,
            'total_price' => 112.80,
            'created_at' => new DateTime
        ]);

        Order::insert([
            'customer_id' => 2,
            'total_price' => 219.75,
            'created_at' => new DateTime
        ]);

        Order::insert([
            'customer_id' => 3,
            'total_price' => 1275.18,
            'created_at' => new DateTime
        ]);
    }
}
