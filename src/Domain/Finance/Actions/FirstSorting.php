<?php

namespace Domain\Finance\Actions;

use Domain\Customer\Models\Organization;
//use Domain\Finance\Models\CompensationItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Első rendezési cél annak kimutatása, hogy a regisztrált ügyféltől (TAG)
 * feldolgozásra átvett adatállomány [CompensationItem::class - rekordok]
 * tartalmaz-e olyan tételeket, amelyek azonos üzleti partneréhez kötődnek
 * és az egyik tétel, vagy azok halmaza Debt (passzíva, azaz tartozás T),
 * a másik tétel, vagy annak halmaza Claim (aktíva, azaz követelés K) jellegű.
 * A tételek T és K elemei  összesítendők, és a kisebb részösszeg értékéig a tételeket
 * TAG saját könyvelésében (pl.: egy elszámolási számlán) összevezetheti.
 * A TAG kötelezettsége, hogy az elszámolást az érintett partnerével azonos időben és értékben hajtsa végre.
 * Az elszámolás végrehajtása, egyenértékű a pénzügyi teljesítéssel.
 *
 * Lásd: Ptk. X. Fejezet - Beszámítás
 * 6:49. § [Pénzkövetelések beszámítása]
 * (1) A kötelezett pénztartozását úgy is teljesítheti, hogy a jogosulttal szemben fennálló lejárt pénzkövetelését a jogosulthoz intézett jognyilatkozattal a pénztartozásába beszámítja.
 * (2) A beszámítás erejéig a kötelezettségek megszűnnek.
 */
class FirstSorting
{
    /**
     * The return values:
     *   -1 on failer
     *   0 if not found
     *   X the count of found records
     */
    public static function find(?int $contact_id = null, ?int $organization_id = null): int
    {
        if (is_null($contact_id)) {
            //Full scanning on the CompensationItem::class database
            return self::scanAllItems();
        }

        if ($contact_id && is_null($organization_id)) {
            return self::scanRelatedItemsToContact($contact_id);
        }

        if ($contact_id && $organization_id) {
            return self::scanRelatedItemsToOrganization($organization_id);
        }

        //wrong call
        return -1;
    }

    /**
     * A cél: megállapítani, hogy vannak-e olyan szervezetek [Organizations] a rendszerben,
     * akikhez mint párhoz CompensationItem::class rekordok rendelhetők hozzá.
     */
    protected static function scanAllItems(): int
    {

        Organization::chunk(20, function (Collection $organizations) {
            foreach ($organizations as $org) {
                // ...
            }
        });

        return 0;
    }

    protected static function scanRelatedItemsToContact(int $contact_id): int
    {

        return 0;
    }

    protected static function scanRelatedItemsToOrganization(int $organization_id): int
    {

        return 0;
    }
}

/*
        CompensationItem::chunk(100, function (Collection $cItems) {
            foreach ($cItems as $cItem) {
                // ...
            }
        });
*/
