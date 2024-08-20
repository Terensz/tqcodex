<?php

namespace Domain\Customer\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CorporateForm: string
{
    use EnumToArray;

    case OPENINC = 'OpenIncorporated'; // Rt.

    case CLOSEDINC = 'ClosedIncorporated'; // Rt.

    case LTD = 'LimitedCompany'; // Kft.

    case GEN = 'GeneralPartnership'; // Közkereseti társaság

    case LPS = 'LimitedPartnership'; // Bt.

    case ENT = 'Entrepreneur'; // Egyéni vállalkozás

    case ASSOC = 'Association'; // Egyesület

    case PUBFDN = 'PublicFoundation'; // Közalapítvány

    case FDN = 'Foundation'; // Alapítvány

    case PRIVATE = 'PrivatePerson'; // Magánszemély

    public function label(): string
    {
        return match ($this) {
            self::OPENINC => __('finance.OpenIncorporated'),
            self::CLOSEDINC => __('finance.ClosedIncorporated'),
            self::LTD => __('finance.LimitedCompany'),
            self::GEN => __('finance.GeneralPartnership'),
            self::LPS => __('finance.LimitedPartnership'),
            self::ENT => __('finance.Entrepreneur'),
            self::ASSOC => __('finance.Association'),
            self::PUBFDN => __('finance.PublicFoundation'),
            self::FDN => __('finance.Foundation'),
            self::PRIVATE => __('finance.PrivatePerson'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
