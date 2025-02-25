<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use DateTime;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::insert([
            'name' => 'Türker Jöntürk',
            'since' => '2014-06-28',
            'revenue' => 494.12,
            'created_at' => new DateTime
        ]);

        Customer::insert([
            'name' => 'Kaptan Devopuz',
            'since' => '2015-01-15',
            'revenue' => 1505.95,
            'created_at' => new DateTime
        ]);

        Customer::insert([
            'name' => 'İsa Sonuyumaz',
            'since' => '2016-02-11',
            'revenue' => 0,
            'created_at' => new DateTime
        ]);
    }
}
