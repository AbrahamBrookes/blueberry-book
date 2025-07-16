<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Customer $customer;
    private CustomerCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = CustomerCategory::factory()->create();
        $this->customer = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);
    }

    public function test_authenticated_user_can_create_contact_and_attach_to_customer()
    {
        $contactData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), $contactData);

        // ContactController redirects to customers.show which redirects to customers.edit
        $response->assertRedirect(route('customers.show', $this->customer->id));
        $response->assertSessionHas('success', 'Contact created successfully.');

        // Check contact was created
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $contact = Contact::where('first_name', 'John')->where('last_name', 'Doe')->first();

        // Check contact is attached to customer
        $this->assertDatabaseHas('contact_customer', [
            'contact_id' => $contact->id,
            'customer_id' => $this->customer->id,
        ]);

        // Verify relationship
        $this->assertTrue($this->customer->fresh()->contacts->contains($contact));
        $this->assertTrue($contact->fresh()->customers->contains($this->customer));
    }

    public function test_guest_cannot_create_contact()
    {
        $contactData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->post(route('contacts.store'), $contactData);
        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('contact_customer', 0);
    }

    public function test_contact_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'customer_id'
        ]);

        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('contact_customer', 0);
    }

    public function test_contact_creation_validates_customer_exists()
    {
        $contactData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'customer_id' => 999, // Non-existent customer
        ];

        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors(['customer_id']);

        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('contact_customer', 0);
    }

    public function test_contact_first_name_is_required()
    {
        $contactData = [
            'last_name' => 'Doe',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors(['first_name']);
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_contact_last_name_is_required()
    {
        $contactData = [
            'first_name' => 'John',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors(['last_name']);
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_authenticated_user_can_update_contact()
    {
        $contact = Contact::factory()->create();
        $contact->customers()->attach($this->customer);

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('contacts.update', $contact), $updateData);

        $response->assertRedirect(route('customers.edit', $this->customer->id));
        $response->assertSessionHas('success', 'Contact updated successfully.');

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }

    public function test_guest_cannot_update_contact()
    {
        $contact = Contact::factory()->create();

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'customer_id' => $this->customer->id,
        ];

        $response = $this->put(route('contacts.update', $contact), $updateData);
        $response->assertRedirect(route('login'));

        // Verify contact wasn't updated
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
        ]);
    }

    public function test_contact_update_validates_required_fields()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)
            ->put(route('contacts.update', $contact), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name'
        ]);
    }

    public function test_contact_update_customer_id_is_provided_for_redirect()
    {
        $contact = Contact::factory()->create();

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'customer_id' => 999, // Non-existent customer but just for redirect
        ];

        $response = $this->actingAs($this->user)
            ->put(route('contacts.update', $contact), $updateData);

        // Should redirect to customers.edit even with non-existent customer_id
        // since validation doesn't check customer_id exists
        $response->assertRedirect(route('customers.edit', 999));
    }

    public function test_authenticated_user_can_delete_contact()
    {
        $contact = Contact::factory()->create();
        $contact->customers()->attach($this->customer);

        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
        $this->assertDatabaseHas('contact_customer', [
            'contact_id' => $contact->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('contacts.destroy', $contact));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Contact deleted successfully.');

        // Contact should be completely deleted
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);

        // Pivot table entries should also be removed
        $this->assertDatabaseMissing('contact_customer', [
            'contact_id' => $contact->id,
        ]);
    }

    public function test_guest_cannot_delete_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contacts.destroy', $contact));
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
    }

    public function test_contact_can_be_attached_to_multiple_customers()
    {
        $contact = Contact::factory()->create();
        $customer2 = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        // Manually attach to both customers (since contact creation only attaches to one)
        $contact->customers()->attach([$this->customer->id, $customer2->id]);

        $this->assertDatabaseHas('contact_customer', [
            'contact_id' => $contact->id,
            'customer_id' => $this->customer->id,
        ]);

        $this->assertDatabaseHas('contact_customer', [
            'contact_id' => $contact->id,
            'customer_id' => $customer2->id,
        ]);

        $this->assertCount(2, $contact->fresh()->customers);
    }

    public function test_deleting_contact_removes_all_customer_relationships()
    {
        $contact = Contact::factory()->create();
        $customer2 = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        // Attach to multiple customers
        $contact->customers()->attach([$this->customer->id, $customer2->id]);

        $this->assertDatabaseCount('contact_customer', 2);

        $response = $this->actingAs($this->user)
            ->delete(route('contacts.destroy', $contact));

        $response->assertSessionHas('success', 'Contact deleted successfully.');

        // All pivot relationships should be removed
        $this->assertDatabaseCount('contact_customer', 0);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);

        // Customers should still exist
        $this->assertDatabaseHas('customers', ['id' => $this->customer->id]);
        $this->assertDatabaseHas('customers', ['id' => $customer2->id]);
    }

    public function test_contact_name_fields_can_contain_various_characters()
    {
        $contactData = [
            'first_name' => "Jean-François O'Connor",
            'last_name' => "van der Berg-Smith",
            'customer_id' => $this->customer->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('contacts.store'), $contactData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'first_name' => "Jean-François O'Connor",
            'last_name' => "van der Berg-Smith",
        ]);
    }

    public function test_contact_update_with_different_customer_maintains_relationships()
    {
        $contact = Contact::factory()->create();
        $customer2 = Customer::factory()->create([
            'customer_category_id' => $this->category->id
        ]);

        // Initially attach to first customer
        $contact->customers()->attach($this->customer);

        // Update contact and specify different customer
        $updateData = [
            'first_name' => 'Updated Name',
            'last_name' => $contact->last_name,
            'customer_id' => $customer2->id,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('contacts.update', $contact), $updateData);

        $response->assertRedirect(route('customers.edit', $customer2->id));

        // Contact should be updated
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Updated Name',
        ]);

        // Original relationship should still exist
        $this->assertDatabaseHas('contact_customer', [
            'contact_id' => $contact->id,
            'customer_id' => $this->customer->id,
        ]);
    }
}
