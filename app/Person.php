<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	public function user()
	{
		return $this->hasOne(User::class);
	}

	public function outputs()
	{
		return $this->hasMany(Output::class);
	}
}
