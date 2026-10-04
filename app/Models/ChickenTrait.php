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

    protected $fillable = ['name', 'icon'];

    /**
     * The icons a trait may use.
     *
     * These keys are the values stored in the icon column, so each one must
     * also exist in the <x-icon> map or the glyph silently disappears. The
     * admin picker and the validation rule both read this list, so the two
     * cannot drift apart.
     *
     * @return list<string>
     */
    public static function icons(): array
    {
        return [
            'sun', 'heart', 'zap', 'star', 'crown', 'flame', 'cloud', 'eye',
            'hand', 'footprints', 'leaf', 'volume', 'anchor', 'sparkles',
            'glasses', 'paw-print', 'bug', 'ghost',
        ];
    }

    /**
     * The icon to draw for this trait.
     *
     * Traits created before icons existed, or with the field left blank, fall
     * back to the generic tag rather than rendering nothing.
     */
    public function iconOrDefault(): string
    {
        return $this->icon ?? 'tag';
    }

    public function chickens(): BelongsToMany
    {
        return $this->belongsToMany(Chicken::class, 'chicken_trait', 'trait_id', 'chicken_id');
    }
}
