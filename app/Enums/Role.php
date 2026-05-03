<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: int
{
    use Enum;

    case ADMINISTRATOR = 1;
    case VENDOR = 2;
    case CUSTOMER = 3;
}
