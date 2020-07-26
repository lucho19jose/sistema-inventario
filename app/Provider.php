<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function inputs()
    {
        return $this->hasMany(Input::class);
    }
}
