<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
	protected $fillable = ['name', 'ruc', 'address', 'phone', 'email'];

    public function inputs()
    {
        return $this->hasMany(Input::class);
    }
}
