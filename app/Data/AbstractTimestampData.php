<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

abstract class AbstractTimestampData extends Data
{
    public function __construct(
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?CarbonImmutable $created_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?CarbonImmutable $updated_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?CarbonImmutable $deleted_at = null,
    ) {
    }
}
