<?php

namespace App\Models\Traits;

trait Uuid
{

    public static function bootUuid()
    {
        static::creating(function ($obj) {
            $obj->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
    }
}
