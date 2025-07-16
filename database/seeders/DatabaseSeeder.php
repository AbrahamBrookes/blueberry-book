<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		// seed the dev admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
			'password' => Hash::make('password'),
        ]);

		// seed categories first so that we can use them in the customers
		$this->call(CustomerCategorySeeder::class);

		// seed the customers
		$this->call(CustomerSeeder::class);

        // then contacts
        $this->call(ContactSeeder::class);
    }
}
