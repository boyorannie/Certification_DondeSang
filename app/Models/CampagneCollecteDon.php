<?php

namespace App\Models;

use App\Models\StructureSante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampagneCollecteDon extends Model
{
    use HasFactory;

    public function StructureSante(): BelongsTo
     {
         return $this->belongsTo(StructureSante::class);
     }
}
