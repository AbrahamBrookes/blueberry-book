<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CustomerCategories are assigned to Customers to define their subscription level. At time of writing
 * we only have Bronze, Silver and Gold. This could be an enum, but for the sake of future-proofing
 * we will store this in the database so maybe users can manage them in the future.
 * 
 * Note that we called this "CustomerCategory" instead of just "Category" to avoid confusion as many things
 * can be categorized so we don't want to have such a generic model name.
 */
class CustomerCategory extends Model
{
    use HasFactory;

	public $fillable = [
		'name',
	];
}
