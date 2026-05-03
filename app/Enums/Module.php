<?php

declare(strict_types=1);

namespace App\Enums;

enum Module: string
{
    use Enum;

    case USER = 'user';
}
