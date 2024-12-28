<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'roles';
    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
