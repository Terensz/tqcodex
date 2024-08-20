<?php

namespace Domain\Shared\Helpers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Crypter
{
    public static function encrypt(string $token): string
    {
        return Crypt::encryptString($token);
    }

    public static function decrypt(string $encryptedValue): string
    {
        $decrypted = null;
        try {
            $decrypted = Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            // ...
        }

        return $decrypted;
        // return json_decode(Crypt::decrypt($encryptedData), true);
    }
}
