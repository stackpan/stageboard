<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Column
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $board_id
 * @property string|null $next_column_id
 * @property-read \App\Models\Board $board
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $cards
 * @property-read int|null $cards_count
 * @method static \Illuminate\Database\Eloquent\Builder|Column newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Column newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Column query()
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereNextColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Column extends Model
{
    use HasFactory, HasUlids;
    
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);    
    }
    
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);    
    }
}
