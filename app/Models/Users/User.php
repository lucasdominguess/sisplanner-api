<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User  extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\Users\UserFactory> */
    use HasFactory,Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status_id',
    ];
    protected $hidden = [
        'password',

    ];
     protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
       public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Você pode adicionar claims customizadas aqui se precisar
    }
}
