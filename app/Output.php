<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $fillable = ['user_id', 'staff_id', 'branch_id', 'voucher_type', 'voucher_serie', 'voucher_number', 'observation'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
