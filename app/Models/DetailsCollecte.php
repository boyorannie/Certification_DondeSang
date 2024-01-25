<?php

namespace App\Models;

use App\Models\GroupeSanguin;
use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailsCollecte extends Model
{
    use HasFactory;
    public function groupe()
    {
        return $this->belongsToMany(GroupeSanguin::class, 'groupe_id', 'name');
    }

    public function campagne()
    {
        return $this->belongsToMany(CampagneCollecteDon::class, 'campagne_id');
    }
}
