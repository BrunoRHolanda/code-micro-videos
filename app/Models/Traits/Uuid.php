<?php

namespace App\Models\Traits;

use Str;

trait Uuid
{
    public static function bootUUid()
    {
        static::creating(fn ($obj) => $obj->id = Str::orderedUuid());
    }
}
