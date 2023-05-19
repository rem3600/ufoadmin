<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Alien;

class Country extends Model
{
    use HasFactory;

    // make a hasmany relation to alien
    public function aliens()
    {
        return $this->hasMany(Alien::class);
    }
}
