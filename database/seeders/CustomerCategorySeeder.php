<?php

namespace Database\Seeders;

use App\Models\CustomerCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // we only have three CustomerCategories at time of writing
		$categories = [
			['name' => 'Bronze'],
			['name' => 'Silver'],
			['name' => 'Gold'],
		];

		foreach ($categories as $category) {
			CustomerCategory::factory()->create($category);
		}
    }
}
