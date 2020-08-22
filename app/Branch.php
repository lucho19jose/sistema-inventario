<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function outputs()
    {
        return $this->hasMany(Output::class);
    }
}
