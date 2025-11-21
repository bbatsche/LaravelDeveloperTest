<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Profile extends Model
{
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function Address(): HasOne
    {
        return $this->hasOne(Address::class);
    }
}
