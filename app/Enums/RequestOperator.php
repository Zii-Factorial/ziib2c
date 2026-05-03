<?php

declare(strict_types=1);

namespace App\Enums;

enum RequestOperator: string
{
    use Enum;

    case EQ = 'eq';
    case EQ_DATE = 'eq_date';
    case NE = 'ne';
    case NE_DATE = 'ne_date';
    case GT = 'gt';
    case GTE = 'gte';
    case LT = 'lt';
    case LTE = 'lte';
    case STARTS = 'starts';
    case ENDS = 'ends';
    case CONTS = 'conts';
    case EXCL = 'excl';
    case STARTL = 'startL';
    case ENDL = 'endL';
    case CONTL = 'contL';
    case EXCLL = 'exclL';
    case IN = 'in';
    case NOT_IN = 'notin';
    case IS_NULL = 'isnull';
    case NOT_NULL = 'notnull';
    case BETWEEN = 'between';
}
