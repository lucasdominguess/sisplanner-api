<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\Users\RoleFactory> */
    use HasFactory;

    protected $table = 'roles';

  public  $timestamps = false;
}
