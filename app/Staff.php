<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
	protected $fillable = ['first_name', 'last_name', 'dni', 'address', 'phone', 'email'];

	public function user()
	{
		return $this->hasOne(User::class);
	}

	public function outputs()
	{
		return $this->hasMany(Output::class);
	}
}
