<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'role_name',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
