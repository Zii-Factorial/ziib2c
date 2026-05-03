<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;

class UserRepository extends AbstractRepository
{
    /**
     * @return class-string<User>
     */
    public function model(): string
    {
        return User::class;
    }

    public function create(array $attributes): mixed
    {
        return $this->createOrUpdate($attributes);
    }

    public function update(array $attributes, $id): mixed
    {
        return $this->createOrUpdate($attributes, $id);
    }

    /**
     * @throws ValidatorException
     */
    public function createOrUpdate(array $attribute, $id = null): mixed
    {
        $attribute = Arr::except($attribute, ['id', 'password_confirmation']);

        if (! Arr::get($attribute, 'password')) {
            $attribute = Arr::except($attribute, ['password']);
        }

        $user = $id ? parent::update($attribute, $id) : parent::create($attribute);

        return $this->parserResult($user);
    }

    /**
     * @param  array{name: string, email: string}  $attributes
     */
    public function updateProfile(User $user, array $attributes): User
    {
        $emailHasChanged = $user->email !== $attributes['email'];

        $user->forceFill([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'email_verified_at' => $emailHasChanged ? null : $user->email_verified_at,
        ])->save();

        return $user->refresh();
    }

    public function updatePassword(User $user, string $password): User
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();

        return $user->refresh();
    }
}
