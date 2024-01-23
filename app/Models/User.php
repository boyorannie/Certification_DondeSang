<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Donateur;
use App\Models\StructureSante;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Utiliser Illuminate\Foundation\Auth\User au lieu de Illuminate\Contracts\Auth\Authenticatable

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable. 
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function role()
     {
         return $this->belongsTo(Role::class);
     }

     public function structuresante(): HasOne
     {
         return $this->hasOne(StructureSante::class);
     }

     public function donateur(): HasOne
     {
         return $this->hasOne(Donateur::class);
     }

     public function admin(): HasOne
     {
         return $this->hasOne(Admin::class);
     }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ]; 

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
}
