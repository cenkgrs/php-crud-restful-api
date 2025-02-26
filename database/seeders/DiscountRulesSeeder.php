<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiscountRules;
use DateTime;

class DiscountRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscountRules::insert([
            'name' => '10_PERCENT_OVER_1000',
            'price' => 1000.00,
            'count' => 1,
            'category_id' => null,
            'discount_type' => 'percentange',
            'discount_amount' => 10,
            'created_at' => new DateTime
        ]);

        DiscountRules::insert([
            'name' => 'BUY_5_GET_1',
            'price' => null,
            'count' => 5,
            'category_id' => 2,
            'discount_type' => 'giveaway',
            'discount_amount' => 1,
            'created_at' => new DateTime
        ]);

        DiscountRules::insert([
            'name' => 'BUY_2_GET_20_PERCENT',
            'price' => null,
            'count' => 2,
            'category_id' => 1,
            'discount_type' => 'percentange',
            'discount_amount' => 20,
            'created_at' => new DateTime
        ]);
    }
}
