<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

/**
 * Egyelőre ez egy tévedés miatt készült, az Organization model vat_code-járól azt hittem, hogy ez az.
 * Viszont ezek hátha kellenek később, így bent hagytam a kódban.
 * Szóval ezek: nem ÁFA-kód, hanem: ÁFA-típus!
 */
enum VatType: string
{
    use EnumToArray;

    case AAM = 'AAM'; // Alanyi adómentes

    case TAM = 'TAM'; // Tárgyi adómentes

    case NAM = 'NAM'; // Egyéb nemzetközi ügyletekhez kapcsolódó jogcímen megállapított adómentesség

    case UNKNOWN = 'UNKNOWN'; // 3.0 előtti számlára hivatkozó, illetve előzmény nélküli módosító és sztornó számlák esetén használható, ha nem megállapítható az érték

    case ATK = 'ATK'; // ATK Áfa tárgyi hatályán kívül

    case EUFAD37 = 'EUFAD37'; // Áfa tv. 37. §-a alapján másik tagállamban teljesített, fordítottan adózó ügylet

    case EUFADE = 'EUFADE'; // Másik tagállamban teljesített, nem az Áfa tv. 37. §-a alá tartozó, fordítottan adózó ügylet

    case EUE = 'EUE'; // Másik tagállamban teljesített, nem fordítottan adózó ügylet

    case HO = 'HO'; // Harmadik országban teljesített ügylet

    case REFUNDABLE_VAT = 'REFUNDABLE_VAT'; // Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének meg kell térítenie

    case NONREFUNDABLE_VAT = 'NONREFUNDABLE_VAT'; // Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének nem kell megtérítenie

    case KBAET = 'KBAET'; // A Közösség másik tagállamában regisztrált adóalany számára történt termékértékesítés, amennyiben a termék az adott tagállamba került elszállításra. Az új közlekedési eszköz értékesítése a KBAUK esethez tartozik. A vevő közösségi adószámát a számlán kötelező eltüntetni.

    case KBAUK = 'KBAUK'; // Új közlekedési eszköz másik tagállamba történő értékesítése. A vevő nem feltétlenül adóalany, lehet például magánszemély is, ezért közösségi adószám nem feltétlenül jelenik meg a számlán. Az Áfa törvény 259. § 25. pontjában felsorolt adatok a számla kötelező adattartalmát képezik.

    case EAM = 'EAM'; // Belföldön teljesített termékértékesítés, aminek a következményeként a terméket kiléptetik harmadik országba (termékexport). A jogszabály alapján olyan speciális esetek is idetartoznak, mint például a nemzetközi szerződés alapján érvényesülő adómentesség.

    public function label(): string
    {
        return match ($this) {
            self::AAM => __('finance.AAMLabel'),
            self::TAM => __('finance.TAMLabel'),
            self::NAM => __('finance.NAMLabel'),
            self::UNKNOWN => __('finance.UNKNOWNLabel'),
            self::ATK => __('finance.ATKLabel'),
            self::EUFAD37 => __('finance.EUFAD37Label'),
            self::EUFADE => __('finance.EUFADELabel'),
            self::EUE => __('finance.EUELabel'),
            self::HO => __('finance.HOLabel'),
            self::REFUNDABLE_VAT => __('finance.REFUNDABLEVATLabel'),
            self::NONREFUNDABLE_VAT => __('finance.NONREFUNDABLEVATLabel'),
            self::KBAET => __('finance.KBAETDescription'),
            self::KBAUK => __('finance.KBAUKDescription'),
            self::EAM => __('finance.EAMDescription'),
        };
    }
}
