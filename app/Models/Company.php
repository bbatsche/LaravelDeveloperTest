<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Company extends Model
{
    protected $fillable = [
        'name',
        'catch_phrase',
        'boilerplate',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
