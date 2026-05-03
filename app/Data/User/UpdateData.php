<?php

namespace App\Data\User;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $password_confirmation = null,
    ) {
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($context->fullPayload['id'] ?? null),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }
}
