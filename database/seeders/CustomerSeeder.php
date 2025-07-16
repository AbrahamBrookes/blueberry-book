<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // just add a single customer for the sake of dev
		Customer::factory()
            ->count(3)
            ->create([
                'customer_category_id' => CustomerCategory::inRandomOrder()->first()->id,
            ]);
    }
}
