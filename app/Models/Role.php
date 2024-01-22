<?php

namespace App\Models;

use App\Models\User;
use App\Models\StructureSante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];
     
     public function users(): HasMany
    {
        return $this->HasMany(User::class);
    }
    public function structure(): HasMany
    {
        return $this->HasMany(StructureSante::class);
    }
}
