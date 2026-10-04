<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class ChickenTrait extends Model
{
    /** @use HasFactory<\Database\Factories\ChickenTraitFactory> */
    use HasFactory;
}
