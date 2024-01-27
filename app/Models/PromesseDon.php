<?php

namespace App\Models;

use App\Models\Donateur;
use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromesseDon extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function CampagneCollecte(): BelongsTo
     {
         return $this->belongsTo(CampagneCollecteDon::class,'campagne_id');
     }
     public function Donateur(): BelongsTo
     {
         return $this->belongsTo(Donateur::class,'donateur_id');
     }
}
