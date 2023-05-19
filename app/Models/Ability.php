<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Alien;

class Ability extends Model
{
    use HasFactory;
    use SoftDeletes;

/*     public function getCombinedAttribute()
        {
            return $this->name . ' (' . $this->damage . ')';
        }
        public $additional_attributes = ['combined']; */

    // create a many2many relationship with aliens
    public function aliens()
    {
        return $this->belongsToMany(Alien::class);
    }
}
