<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBoard extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'opened_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
    ];
}
