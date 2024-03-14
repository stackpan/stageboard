<?php

namespace App\Models;

use App\Enums\BoardPermission;
use App\Events\BoardChangedEvent;
use App\Events\BoardCreated;
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
 * @property string $alias_id
 * @property string $name
 * @property string|null $thumbnail_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $owner_id
 * @property bool $is_public
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Column> $columns
 * @property-read int|null $columns_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\BoardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board query()
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereAliasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Board extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    protected $dispatchesEvents = [
        'created' => BoardCreated::class,
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
        return $this->belongsToMany(User::class, 'user_board')->using(UserBoard::class)->withPivot('opened_at');
    }

    public function getUserPermission(User $user): BoardPermission | null
    {
        $result = $this->users()
            ->withPivot('permission')
            ->whereUserId($user->id)
            ->first();

        return optional($result, fn ($board) => $board->pivot->permission);
    }
}
