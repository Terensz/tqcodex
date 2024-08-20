<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CountyCode: string
{
    use EnumToArray;

    case BARANYA_1 = '02';

    case BARANYA_2 = '22';

    case BACS_KISKUN_1 = '03';

    case BACS_KISKUN_2 = '23';

    case BEKES_1 = '04';

    case BEKES_2 = '24';

    case BAZ_1 = '05';

    case BAZ_2 = '25';

    case CSONGRAD_1 = '06';

    case CSONGRAD_2 = '26';

    case FEJER_1 = '07';

    case FEJER_2 = '27';

    case GYMS_1 = '08';

    case GYMS_2 = '28';

    case HAJDU_BIHAR_1 = '09';

    case HAJDU_BIHAR_2 = '29';

    case HEVES_1 = '10';

    case HEVES_2 = '30';

    case KOMAROM_ESZTERGOM_1 = '11';

    case KOMAROM_ESZTERGOM_2 = '31';

    case NOGRAD_1 = '12';

    case NOGRAD_2 = '32';

    case PEST_1 = '13';

    case PEST_2 = '33';

    case SOMOGY_1 = '14';

    case SOMOGY_2 = '34';

    case SZABOLCS_SZATMAR_BEREG_1 = '15';

    case SZABOLCS_SZATMAR_BEREG_2 = '35';

    case JNKSZ_1 = '16';

    case JNKSZ_2 = '36';

    case TOLNA_1 = '17';

    case TOLNA_2 = '37';

    case VAS_1 = '18';

    case VAS_2 = '38';

    case VESZPREM_1 = '19';

    case VESZPREM_2 = '39';

    case ZALA_1 = '20';

    case ZALA_2 = '40';

    case NORTH_BUDAPEST = '41';

    case EAST_BUDAPEST = '42';

    case SOUTH_BUDAPEST = '43';

    case NAV_PRIORITY_CUSTOMERS_OFFICE = '44';

    case NAV_SPECIAL_AFFAIRS_OFFICE = '51';

    public function label(): string
    {
        $base = match ($this) {
            self::BARANYA_1 => __('finance.CountyBaranya'),
            self::BARANYA_2 => __('finance.CountyBaranya'),
            self::BACS_KISKUN_1 => __('shared.CountyBacsKiskun'),
            self::BACS_KISKUN_2 => __('shared.CountyBacsKiskun'),
            self::BEKES_1 => __('shared.CountyBekes'),
            self::BEKES_2 => __('shared.CountyBekes'),
            self::BAZ_1 => __('shared.CountyBAZ'),
            self::BAZ_2 => __('shared.CountyBAZ'),
            self::CSONGRAD_1 => __('shared.CountyCsongrad'),
            self::CSONGRAD_2 => __('shared.CountyCsongrad'),
            self::FEJER_1 => __('shared.CountyFejer'),
            self::FEJER_2 => __('shared.CountyFejer'),
            self::GYMS_1 => __('shared.CountyGYMS'),
            self::GYMS_2 => __('shared.CountyGYMS'),
            self::HAJDU_BIHAR_1 => __('shared.CountyHajduBihar'),
            self::HAJDU_BIHAR_2 => __('shared.CountyHajduBihar'),
            self::HEVES_1 => __('shared.CountyHeves'),
            self::HEVES_2 => __('shared.CountyHeves'),
            self::KOMAROM_ESZTERGOM_1 => __('shared.CountyKomaromEsztergom'),
            self::KOMAROM_ESZTERGOM_2 => __('shared.CountyKomaromEsztergom'),
            self::NOGRAD_1 => __('shared.CountyNograd'),
            self::NOGRAD_2 => __('shared.CountyNograd'),
            self::PEST_1 => __('shared.CountyPest'),
            self::PEST_2 => __('shared.CountyPest'),
            self::SOMOGY_1 => __('shared.CountySomogy'),
            self::SOMOGY_2 => __('shared.CountySomogy'),
            self::SZABOLCS_SZATMAR_BEREG_1 => __('shared.CountySzabolcsSzatmarBereg'),
            self::SZABOLCS_SZATMAR_BEREG_2 => __('shared.CountySzabolcsSzatmarBereg'),
            self::JNKSZ_1 => __('shared.CountyJNKSZ'),
            self::JNKSZ_2 => __('shared.CountyJNKSZ'),
            self::TOLNA_1 => __('shared.CountyTolna'),
            self::TOLNA_2 => __('shared.CountyTolna'),
            self::VAS_1 => __('shared.CountyVas'),
            self::VAS_2 => __('shared.CountyVas'),
            self::VESZPREM_1 => __('shared.CountyVeszprem'),
            self::VESZPREM_2 => __('shared.CountyVeszprem'),
            self::ZALA_1 => __('shared.CountyZala'),
            self::ZALA_2 => __('shared.CountyZala'),
            self::NORTH_BUDAPEST => __('finance.NAVAreaNorthBudapest'),
            self::EAST_BUDAPEST => __('finance.NAVAreaEastBudapest'),
            self::SOUTH_BUDAPEST => __('finance.NAVAreaSouthBudapest'),
            self::NAV_PRIORITY_CUSTOMERS_OFFICE => __('finance.NAVAreaPriorityCustomersOffice'),
            self::NAV_SPECIAL_AFFAIRS_OFFICE => __('finance.NAVAreaSpecialAffairsOffice'),
        };

        return $base.' ('.$this->value.')';
    }
}
