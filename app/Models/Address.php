<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Address extends Model
{
    protected array $fillable = [
        'street',
        'suite',
        'city',
        'zip',
        'zip4',
        'latitude',
        'longitude',
    ];

    public function Profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
