<?php

// hu/finance.php

return [
    'TaxpayerId' => 'Adószám',
    'VatCode' => 'ÁFA-kód',
    'IsCompensed' => 'Már kompenzálva',
    'VatBanned' => 'Adószám letiltva',

    'InvoiceIdForCompensation' => 'Kompenzációra váró számla azonosítója',
    'InvoiceInternalId' => 'Belsős számlaazonosító',
    'DueDate' => 'Fizetési határidő',
    'InvoiceDate' => 'Számlakiállítási dátum',
    'FulfilmentDate' => 'Teljesítési dátum',
    'LateInterestRate' => 'Késedelmi kamat mértéke',
    'LateInterestAmount' => 'Késedelmi kamat összege',
    'InvoiceAmount' => 'A számla végösszege',
    'InvoiceType' => 'A számla típusa',
    'PaymentMode' => 'Fizetés módja',
    'Currency' => 'Pénznem',
    'IsPartAmount' => 'Részösszeg?',
    'IsDisputed' => 'Vitatott?',
    'PartnerOrg' => 'Partner szervezet',

    'OpenIncorporated' => 'NYRT.',
    'ClosedIncorporated' => 'ZRT.',
    'LimitedCompany' => 'KFT.',
    'GeneralPartnership' => 'Közkereseti társaság',
    'LimitedPartnership' => 'Betéti társaság',
    'Entrepreneur' => 'Egyéni vállalkozó',
    'Association' => 'Egyesület',
    'PublicFoundation' => 'Közalapítvány',
    'Foundation' => 'Alapítvány',
    'PrivatePerson' => 'Magánszemély',

    'Debt' => 'Tartozik',
    'Claim' => 'Követel',
    'Transfer' => 'Átutalás',
    'HUF' => 'Magyar forint',
    'EUR' => 'Euro',
    'USD' => 'USA dollár',
    'STK' => 'Szentkorona',
    'PartnerName' => 'Partner neve',
    'PartnerTaxpayerId' => 'Partner adószáma',
    'PartnerEUTaxId' => 'Partner EU adószáma',
    'PartnerAddress' => 'Partner címe',
    'PartnerEmail' => 'Partner e-mail címe',
    'PartnerPhone' => 'Partner telefonszáma',
    'PartnerContact' => 'Partner kapcsolattartója',

    'ThisCompensationItemIsNotOwned' => 'Ez a kompenzációs tétel nem saját.',

    'AAMLabel' => 'Alanyi adómentes',
    'TAMLabel' => 'Tárgyi adómentes',
    'NAMLabel' => 'Egyéb nemzetközi ügyletekhez kapcsolódó jogcímen megállapított adómentesség',
    'UNKNOWNLabel' => '3.0 előtti számlára hivatkozó, illetve előzmény nélküli módosító és sztornó számlák esetén használható, ha nem megállapítható az érték',
    'ATKLabel' => 'ATK Áfa tárgyi hatályán kívül',
    'EUFAD37Label' => 'Áfa tv. 37. §-a alapján másik tagállamban teljesített, fordítottan adózó ügylet',
    'EUFADELabel' => 'Másik tagállamban teljesített, nem az Áfa tv. 37. §-a alá tartozó, fordítottan adózó ügylet',
    'EUELabel' => 'Másik tagállamban teljesített, nem fordítottan adózó ügylet ',
    'HOLabel' => 'Harmadik országban teljesített ügylet',
    'REFUNDABLEVATLabel' => 'Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének meg kell térítenie',
    'NONREFUNDABLEVATLabel' => 'Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének nem kell megtérítenie ',
    'KBAETDescription' => 'A Közösség másik tagállamában regisztrált adóalany számára történt termékértékesítés, amennyiben a termék az adott tagállamba került elszállításra. Az új közlekedési eszköz értékesítése a KBAUK esethez tartozik. A vevő közösségi adószámát a számlán kötelező eltüntetni.',
    'KBAUKDescription' => 'Új közlekedési eszköz másik tagállamba történő értékesítése. A vevő nem feltétlenül adóalany, lehet például magánszemély is, ezért közösségi adószám nem feltétlenül jelenik meg a számlán. Az Áfa törvény 259. § 25. pontjában felsorolt adatok a számla kötelező adattartalmát képezik',
    'EAMDescription' => 'Belföldön teljesített termékértékesítés, aminek a következményeként a terméket kiléptetik harmadik országba (termékexport). A jogszabály alapján olyan speciális esetek is idetartoznak, mint például a nemzetközi szerződés alapján érvényesülő adómentesség',

    'NAVAreaNorthBudapest' => 'Észak-Budapest',
    'NAVAreaEastBudapest' => 'Kelet-Budapest',
    'NAVAreaSouthBudapest' => 'Dél-Budapest',
    'NAVAreaSpecialAffairsOffice' => 'NAV Kiemelt Ügyek Adóigazgatósága',
    'NAVAreaPriorityCustomersOffice' => 'NAV Kiemelt Adózók Adóigazgatósága',

    'InvalidHungarianTaxNumberFormat' => 'Érvénytelen formátumú magyar adószám',
    'InvalidHungarianTaxNumber' => 'Érvénytelen magyar adószám',
    'InvalidHungarianTaxNumber2' => 'Az adószámhoz tartozó cégnév eltér a NAV rendszerében tárolttól.',
    'InvalidHungarianTaxNumber3' => 'A partner adószámához tartozó cégnév eltér a NAV rendszerében tárolttól.',
    'InvalidPartnerOrgId' => 'Érvénytelen partner szervezet.',

    'Free' => 'Szabad',
    'Marked' => 'Megjelölve',
    'Occupied' => 'Foglalt',

    'PartnerLocation' => 'Partner régiója',

    // 'attributes' => [
    //     'vat_code' => 'Adószám',
    // ],

];
