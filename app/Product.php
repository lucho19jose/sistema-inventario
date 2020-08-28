<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = ['code', 'category_id', 'name', 'description', 'stock'];

    public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function inputs()
	{
		return $this->belongsToMany(Input::class);
	}

	public function outputs()
	{
		return $this->belongsToMany(Output::class);
	}

}
