<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
	protected $fillable = ['name', 'address', 'city'];

    public function outputs()
    {
        return $this->hasMany(Output::class);
    }
}
