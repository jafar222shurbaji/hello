<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = ['fabric_type'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
