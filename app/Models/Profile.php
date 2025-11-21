<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Profile extends Model
{
    protected array $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'extension',
        'website',
    ];

    protected array $with = ['address', 'company'];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function Address(): HasOne
    {
        return $this->hasOne(Address::class);
    }
}
