<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Board
 *
 * @property string $id
 * @property string $name
 * @property string|null $thumbnail_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Column> $columns
 * @property-read int|null $columns_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board query()
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $opened_at
 * @property-read int|null $opened_at_count
 * @method static \Database\Factories\BoardFactory factory($count = null, $state = [])
 * @property string $alias_id
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereAliasId($value)
 * @mixin \Eloquent
 */
class Board extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_board')->using(UserBoard::class);
    }
}
