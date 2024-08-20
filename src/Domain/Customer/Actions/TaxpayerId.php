<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Illuminate\Support\Str;

class TaxpayerId
{
    public static function check(string $taxid): bool
    {
        if (Str::of($taxid)->contains('-')) {
            $pieces = explode('-', $taxid);
            if (Str::of($pieces[0])->length() < 8) {
                return false;
            }

            return true;
        }
        if (Str::of($taxid)->length() < 8) {
            return false;
        }

        return true;
    }
}
