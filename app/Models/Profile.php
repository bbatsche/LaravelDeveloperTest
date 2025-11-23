<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Profile extends Model
{
    protected $fillable = [
        'profile_id',
        'name',
        'username',
        'email',
        'phone',
        'extension',
        'website',
    ];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function posts(): HasMany
    {
        return $this->HasMany(Post::class, 'profile_id', 'profile_id');
    }
}
