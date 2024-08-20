<?php

namespace Domain\User\Services;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;

class SimpleHasher extends AbstractHasher implements Hasher
{
    public function make($value, array $options = []): string
    {
        return password_hash($value, PASSWORD_DEFAULT, $options);
    }

    public function check($value, $hashedValue, array $options = []): bool
    {
        return password_verify($value, $hashedValue);
    }

    public function needsRehash($hashedValue, array $options = []): bool
    {
        return password_needs_rehash($hashedValue, PASSWORD_DEFAULT, $options);
    }
}
