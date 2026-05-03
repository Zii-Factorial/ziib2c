<?php

namespace App\Data\User;

use App\Data\AbstractTimestampData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class UserData extends AbstractTimestampData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?CarbonImmutable $email_verified_at = null,
        ?CarbonImmutable $created_at = null,
        ?CarbonImmutable $updated_at = null,
        ?CarbonImmutable $deleted_at = null,
    ) {
        parent::__construct($created_at, $updated_at, $deleted_at);
    }
}
