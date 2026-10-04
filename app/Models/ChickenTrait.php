<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Guarded([])]
class ChickenTrait extends Model
{
    /** @use HasFactory<\Database\Factories\ChickenTraitFactory> */
    use HasFactory;

    public function chickens(): BelongsToMany
    {
        return $this->belongsToMany(Chicken::class, 'chicken_trait', 'trait_id', 'chicken_id');
    }
}
