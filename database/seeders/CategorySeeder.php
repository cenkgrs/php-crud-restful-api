<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use DateTime;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            'name' => 'Kategori 1',
            'created_at' => new DateTime
        ]);

        Category::insert([
            'name' => 'Kategori 2',
            'created_at' => new DateTime
        ]);
    }
}
