<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private CustomerCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = CustomerCategory::factory()->create();
    }

    public function test_authenticated_user_can_view_customers_index()
    {
        $customers = Customer::factory()->count(3)->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Customers/Index')
                ->has('customers', 3)
        );
    }

    public function test_guest_cannot_view_customers_index()
    {
        $response = $this->get(route('customers.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_create_customer_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('customers.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Customers/Create')
                ->has('customerCategories')
        );
    }

    public function test_guest_cannot_view_create_customer_form()
    {
        $response = $this->get(route('customers.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_customer()
    {
        $customerData = [
            'name' => 'Test Customer',
            'reference' => 'TC001',
            'customer_category_id' => $this->category->id,
            'started_at' => '2025-01-01',
            'description' => 'Test customer description',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('customers.store'), $customerData);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success', 'Customer created successfully.');

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'reference' => 'TC001',
            'customer_category_id' => $this->category->id,
            'description' => 'Test customer description',
        ]);
    }

    public function test_guest_cannot_create_customer()
    {
        $customerData = [
            'name' => 'Test Customer',
            'reference' => 'TC001',
            'customer_category_id' => $this->category->id,
            'started_at' => '2025-01-01',
        ];

        $response = $this->post(route('customers.store'), $customerData);
        $response->assertRedirect(route('login'));
    }

    public function test_customer_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('customers.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'reference',
            'customer_category_id',
            'started_at'
        ]);

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_customer_creation_validates_customer_category_exists()
    {
        $customerData = [
            'name' => 'Test Customer',
            'reference' => 'TC001',
            'customer_category_id' => 999, // Non-existent
            'started_at' => '2025-01-01',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('customers.store'), $customerData);

        $response->assertSessionHasErrors(['customer_category_id']);
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_customer_show_redirects_to_edit()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('customers.show', $customer));

        $response->assertRedirect(route('customers.edit', $customer));
    }

    public function test_authenticated_user_can_view_customer_edit_form()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('customers.edit', $customer));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Customers/Edit')
                ->where('customer.id', $customer->id)
                ->where('customer.name', $customer->name)
                ->has('customerCategories')
        );
    }

    public function test_guest_cannot_view_customer_edit_form()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->get(route('customers.edit', $customer));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_customer()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $updateData = [
            'name' => 'Updated Customer Name',
            'reference' => 'UC001',
            'customer_category_id' => $this->category->id,
            'started_at' => '2025-01-15',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('customers.update', $customer), $updateData);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success', 'Customer updated successfully.');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Customer Name',
            'reference' => 'UC001',
            'description' => 'Updated description',
        ]);
    }

    public function test_guest_cannot_update_customer()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $updateData = [
            'name' => 'Updated Customer Name',
        ];

        $response = $this->put(route('customers.update', $customer), $updateData);
        $response->assertRedirect(route('login'));
    }

    public function test_customer_update_validates_required_fields()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('customers.update', $customer), []);

        $response->assertSessionHasErrors([
            'name',
            'reference',
            'customer_category_id',
            'started_at'
        ]);
    }

    public function test_authenticated_user_can_delete_customer()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success', 'Customer deleted successfully.');

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_guest_cannot_delete_customer()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $response = $this->delete(route('customers.destroy', $customer));
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_deleting_customer_detaches_all_contacts()
    {
        $customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        $contacts = \App\Models\Contact::factory()->count(2)->create();
        $customer->contacts()->attach($contacts);

        $this->assertCount(2, $customer->contacts);
        $this->assertDatabaseCount('contact_customer', 2);

        $response = $this->actingAs($this->user)
            ->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));

        // Check that pivot table entries are removed
        $this->assertDatabaseCount('contact_customer', 0);

        // Check that contacts still exist (not deleted, just detached)
        foreach ($contacts as $contact) {
            $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
        }

        // Check customer is deleted
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_customer_index_orders_by_name()
    {
        $customerZ = Customer::factory()->create([
            'customer_category_id' => $this->category->id,
            'name' => 'Z Customer'
        ]);

        $customerA = Customer::factory()->create([
            'customer_category_id' => $this->category->id,
            'name' => 'A Customer'
        ]);

        $customerM = Customer::factory()->create([
            'customer_category_id' => $this->category->id,
            'name' => 'M Customer'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Customers/Index')
                ->where('customers.0.name', 'A Customer')
                ->where('customers.1.name', 'M Customer')
                ->where('customers.2.name', 'Z Customer')
        );
    }
}
