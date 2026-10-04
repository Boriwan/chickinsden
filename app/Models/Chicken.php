<?php

namespace App\Models;

use Database\Factories\ChickenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chicken extends Model
{
    /** @use HasFactory<ChickenFactory> */
    use HasFactory;

    protected $fillable = ['name', 'gender', 'birth_date', 'breed_id', 'height', 'weight', 'user_id', 'image'];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function traits(): BelongsToMany
    {
        return $this->belongsToMany(ChickenTrait::class, 'chicken_trait', 'chicken_id', 'trait_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
