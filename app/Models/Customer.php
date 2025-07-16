<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Customers are entities that use our products and services. They have "categories" which are
 * kind of like a subscription level - Bronze, Silver, Gold. They also have contacts as more
 * than one human can be associated with a customer.
 */
class Customer extends Model
{
    use HasFactory;

	public $fillable = [
		'name',
		'reference',
		'started_at',
		'description',
		'customer_category_id',
		'status',
	];

    public $casts = [
        'started_at' => 'datetime',
    ];

	/**
	 * Customers belong to a CustomerCategory.
	 */
	public function customerCategory()
	{
		return $this->belongsTo(CustomerCategory::class);
	}

	/**
	 * Customers have many contacts. This is a many-to-many relationship for future-proofings
	 * sake, as humans might work for multiple customers (ie: contractors, brand development
	 * agencies, etc).
	 */
	public function contacts()
	{
		return $this->belongsToMany(Contact::class);
	}
}
