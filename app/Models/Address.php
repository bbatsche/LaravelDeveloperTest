<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Address extends Model
{
    public function Profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
