<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Post extends Model
{
    protected $fillable = [
        'post_id',
        'profile_id',
        'title',
        'body',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }
}
