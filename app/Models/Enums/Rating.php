<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum Rating: string
{
    case Free = 'L';
    case Ten = '10';
    case Twelve = '12';
    case Fourteen = '14';
    case Sixteen = '16';
    case Eighteen = '18';
}
