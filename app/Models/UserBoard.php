<?php

namespace App\Models;

use App\Enums\BoardPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\UserBoard
 *
 * @property \Illuminate\Support\Carbon $opened_at
 * @property string $user_id
 * @property string $board_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard whereUserId($value)
 * @property BoardPermission $permission
 * @method static \Illuminate\Database\Eloquent\Builder|UserBoard wherePermission($value)
 * @mixin \Eloquent
 */
class UserBoard extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'opened_at',
        'permission'
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'permission' => BoardPermission::class,
    ];
}
