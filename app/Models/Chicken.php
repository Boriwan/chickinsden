<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chicken extends Model
{
    /** @use HasFactory<\Database\Factories\ChickenFactory> */
    use HasFactory;

    protected $fillable = ['name', 'gender', 'born_date', 'breed_id', 'den_id', 'height', 'weight'];
}
