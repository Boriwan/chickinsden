<?php

namespace App\Models;

use Database\Factories\BreedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    /** @use HasFactory<BreedFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * The chickens belonging to this breed.
     *
     * Lets the admin area refuse deleting a breed that chickens still point
     * at, since chickens.breed_id is non-nullable and its foreign key
     * restricts rather than cascades.
     */
    public function chickens(): HasMany
    {
        return $this->hasMany(Chicken::class);
    }
}
