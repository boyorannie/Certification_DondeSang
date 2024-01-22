<?php

namespace App\Models;

use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StuctureSante extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function campagnecollecte(): HasMany
    {
        return $this->HasMany(CampagneCollecteDon::class);
    }
}
