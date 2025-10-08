<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\Users\StatusFactory> */
    use HasFactory;

    protected $table = 'status';

    protected $fillable = ['name'];
    protected $hidden = ['pivot'];
    public $timestamps = false;
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
