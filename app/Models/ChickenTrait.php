<?php

namespace App\Models;

use Database\Factories\ChickenTraitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChickenTrait extends Model
{
    /** @use HasFactory<ChickenTraitFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function chickens(): BelongsToMany
    {
        return $this->belongsToMany(Chicken::class, 'chicken_trait', 'trait_id', 'chicken_id');
    }
}
