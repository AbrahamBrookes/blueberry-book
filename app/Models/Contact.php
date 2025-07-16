<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A Contact is a record of a person and their contact details. Contacts are assigned to Customers
 * so that users can easily reach out to them.
 */
class Contact extends Model
{
    use HasFactory;

	public $fillable = [
		'first_name',
		'last_name',
	];

	/**
	 * Contacts can belong to many Customers. This is a many-to-many relationship for future-proofings
	 * sake, as humans might work for multiple Customers (ie: contractors, brand development
	 * agencies, etc).
	 */
	public function customers()
	{
		return $this->belongsToMany(Customer::class);
	}
}
