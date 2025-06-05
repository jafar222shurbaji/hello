<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wood extends Model
{
    protected $fillable = ['wood_type'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
