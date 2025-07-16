<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the latest customer
        $customer = Customer::latest()->first();

        // seed a couple of contacts for the customer
        Contact::factory()
            ->count(5)
            // associate with the customer (belongsToMany relationship)
            ->hasAttached($customer, [], 'customers')
            ->create();
    }
}
