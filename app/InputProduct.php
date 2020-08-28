<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputProduct extends Model
{
	protected $table = 'input_product';

	protected $fillable = ['product_id', 'input_id', 'quantity', 'price'];

    public $timestamps = false;
}
