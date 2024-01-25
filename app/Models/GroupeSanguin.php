<?php

namespace App\Models;

use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupeSanguin extends Model
{
    use HasFactory;

    public function campagne(): HasMany
     {
         return $this->hasMany(CampagneCollecteDon::class);
     }
}
