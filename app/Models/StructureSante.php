<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StructureSante extends Model
{
    use HasFactory;
    protected $guarded = [];
   
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function campagnecollecte(): HasMany
    {
        return $this->HasMany(CampagneCollecteDon::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
