<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use DateTime;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            'id' => 100,
            'category_id' => 1,
            'name' => 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti',
            'price' => 120.75,
            'stock' => 10,
            'created_at' => new DateTime
        ]);

        Product::insert([
            'id' => 101,
            'category_id' => 1,
            'name' => "Reko Mini Tamir Hassas Tornavida Seti 32'li",
            'price' => 49.50,
            'stock' => 10,
            'created_at' => new DateTime
        ]);

        Product::insert([
            'id' => 102,
            'category_id' => 2,
            'name' => 'Viko Karre Anahtar - Beyaz',
            'price' => 11.28,
            'stock' => 10,
            'created_at' => new DateTime
        ]);

        Product::insert([
            'id' => 103,
            'category_id' => 2,
            'name' => 'Legrand Salbei Anahtar, Alüminyum',
            'price' => 22.80,
            'stock' => 10,
            'created_at' => new DateTime
        ]);

        Product::insert([
            'id' => 104,
            'category_id' => 2,
            'name' => 'Schneider Asfora Beyaz Komütatör',
            'price' => 12.95,
            'stock' => 10,
            'created_at' => new DateTime
        ]);
    }
}
