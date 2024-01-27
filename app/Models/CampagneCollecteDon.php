<?php

namespace App\Models;

use App\Models\PromesseDon;
use App\Models\GroupeSanguin;
use App\Models\StructureSante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampagneCollecteDon extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function StructureSante(): BelongsTo
     {
         return $this->belongsTo(StructureSante::class,'structure_id');
     }

     public function PromesseDon(): HasMany
    {
        return $this->hasMany(PromesseDon::class);
    }
}
