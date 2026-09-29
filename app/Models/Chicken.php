<?php

namespace App\Models;

use Database\Factories\ChickenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chicken extends Model
{
    /** @use HasFactory<ChickenFactory> */
    use HasFactory;

    protected $fillable = ['name', 'gender', 'birth_date', 'breed_id', 'height', 'weight'];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }
}
