<?php

namespace App\Models;

use App\Enums\CardColor;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Card
 *
 * @property string $id
 * @property string $body
 * @property CardColor $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $column_id
 * @property-read \App\Models\Column $column
 * @method static \Database\Factories\CardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Card extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'body',
        'color',
    ];

    protected $casts = [
        'color' => CardColor::class
    ];

    protected $touches = [
        'column',
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
