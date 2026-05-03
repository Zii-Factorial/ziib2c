<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: int
{
    use Enum;

    case INACTIVE = 0;
    case ACTIVE = 1;
}
