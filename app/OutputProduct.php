<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputProduct extends Model
{
	protected $table = 'output_product';

	protected $fillable = ['product_id', 'output_id', 'quantity'];

    public $timestamps = false;
}
