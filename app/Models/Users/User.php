<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\Users\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status_id',
    ];
    
}
