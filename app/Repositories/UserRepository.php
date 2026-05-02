<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;

class UserRepository
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User
    {
        return User::query()->create(Arr::only($attributes, [
            'name',
            'email',
            'password',
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateProfile(User $user, array $attributes): User
    {
        $user->fill(Arr::only($attributes, ['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    public function updatePassword(User $user, string $password): User
    {
        $user->forceFill([
            'password' => $password,
        ])->save();

        return $user;
    }
}
