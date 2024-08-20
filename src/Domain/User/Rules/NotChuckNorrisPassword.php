<?php

namespace Domain\User\Rules;

use Closure;
use Domain\Shared\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class NotChuckNorrisPassword implements ValidationRule
{
    /**
     * This validator only checks if the password is "cHuckNorRis" in any set of letter-cases, or not.
     * Funny guys love to set this as "strong" password.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lowercaseValue = strtolower($value);
        $positionOfChuck = strpos($lowercaseValue, 'chuck');
        $positionOfNorris = strpos($lowercaseValue, 'norris');

        // Checking if both words exist in the password
        if ($positionOfChuck !== false && $positionOfNorris !== false) {
            // Checking sequence of words
            if ($positionOfNorris > $positionOfChuck) {
                $valueWithoutNorris = StringHelper::replaceFromPosition($value, '', $positionOfNorris, mb_strlen('norris'));
                $valueWithoutChuckNorris = StringHelper::replaceFromPosition($valueWithoutNorris, '', $positionOfChuck, mb_strlen('chuck'));

                if (strlen($valueWithoutChuckNorris) === 0) {
                    $fail('shared.PasswordIsTooStrong')->translate();
                }
            }
        }
    }
}
