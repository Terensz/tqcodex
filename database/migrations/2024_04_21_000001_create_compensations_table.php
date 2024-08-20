<?php

declare(strict_types=1);

use Domain\Finance\Enums\CompensationItemStatus;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table compensationitems
     * Ez a tábla tartalmazza az ügyfelek által felvitt számla adatokat,
     * amiket szeretnének az elszámolási rendszerben érvényesíteni.
     */
    public function up(): void
    {
        Schema::create('compensationitems', function (Blueprint $table): void {
            $table->id();
            //ő vitte vagy töltötte fel a számlát
            $table->foreignId('contact_id')->constrained();
            //Ehhez a céghez tartozik a felvitt számla
            $table->foreignId('organization_id')->constrained();
            //A kompenzálásra felvitt számla-okmány sorszáma. Ezt használja a NAV is az ÁFA visszaigényléskor (kis- nagybetű, szám és jel lehet a tartalma)
            $table->string('invoice_id_for_compensation')->index();
            //Pénzügyi nyilvántartási szám (a szerződő ügyfél nyilvántartási rendszerétől függő adat) ezzel azonosítja a Contact/Organization cég könyvelési rendszere a számlát
            $table->string('invoice_internal_id')->nullable()->index();
            //Fizetési határidő: formátuma (2024-05-01). Az adatbeviteli rendszernek fel kell ismernie a más formátumokat, pl. a 2024.05.01 formátumot, és átalakítani a nativ mysql date formátumra.
            $table->date('due_date')->index();
            //A számla kiállításának napja: (Formátum ugyanaz, nem kötelező)
            $table->date('invoice_date')->nullable()->index();
            //A számlához kapcsolódó teljesítés dátuma (formátuma ugyanaz, nem kötelező)
            $table->date('fulfilment_date')->nullable()->index();
            //Késedelmi kamat mértéke: pl. 10.75% (a % jelet nem kell megadnia) kötelezően érvényesítendő, az elengedése engedménynek minősül, annak módjára számviteli politikában rendelkezni kell. Tehát itt helyet kell adnunk a késedelmi kamat részére. Ha elengedi a szerződő ügyfelünk, akkor jelöltessük vele 0 % értékkel (ami a default is). A felszámított késedelmi kamatban adott engedmény alkalmas lehet majd a kisösszegű egyenlegek leírására.
            $table->decimal('late_interest_rate', 4, 2)->default(0.0);
            //A késedelmi kamat összege. Ez nem adatbeviteli mező, mert a kompenzálás napján számoljuk az egyéb adatokból, ha a késedelmi kamat nem nulla. A számla összegeket egész mezőben tároljuk.
            $table->unsignedBigInteger('late_interest_amount')->default(0);
            //A kompenzálandó számla végösszege. Csak nullánál nagyobb pozitív szám. A nyilvántartás cent alapon történik, egész számokkal, és nem lebegő pontosokkal.
            $table->unsignedBigInteger('invoice_amount');
            //A számla típusa: Tartozik (Debt) vagy követel (Claim) PHP enum-ból: InvoiceTypes::Claim->value
            $table->string('invoice_type', 16)->default(InvoiceType::CLAIM->value);
            //A kompenzálandó számla fizetési módja: PHP enum-ból: PaymentModes::Transfer->value
            $table->string('payment_mode', 16)->default(PaymentMode::TRANSFER->value);
            //A számla pénzneme. A felvitelkor ez lehet default HUF, egyébként az elszámolás alapja pedig az STK
            $table->string('currency', 3)->default(Currency::HUF->value);
            //Megjegyzés (a számla illetve az egyéb tartozás/követelés rövid tartalma)
            $table->text('description')->nullable();
            //Részösszeg? Az alapértelmezett az NEM.
            $table->boolean('is_part_amount')->default(false)->index();
            //Vitatott számla? Az alapértelmezett az NEM.
            $table->boolean('is_disputed')->default(false)->index();
            //
            $table->string('partner_unique_id')->index();
            // Megegyezik az organization_id-val, ha már regisztrált ügyfelünk
            // $table->string('partner_org_id')->nullable()->index(); // Eredeti
            $table->unsignedBigInteger('partner_org_id')->nullable()->unsigned()->index(); // Terence belepiszkált és átírta string-ről unsignedBigInteger-re.
            $table->foreign('partner_org_id')->references('id')->on('organizations');
            //Az ügyfél partnerének alap adatai
            // Partner régiója
            $table->string('partner_location')->nullable()->index();
            //A partner cég neve (aki a kompenzálandó számlán vagy vevő [claim - követel] vagy eladó [debt - tartozik] esetben)
            $table->string('partner_name')->index();
            //Magyar cég esetén az adószáma
            $table->string('partner_taxpayer_id', 15)->nullable()->index();
            //EU cég esetén az EU adószáma
            $table->string('partner_eutaxid')->nullable()->index();
            //Nem magyar és nem EU cég esetén az egyéb adó vagy cégazonosító száma. Ebből a 3 mezőből legalább az egyiket meg kell adni.
            $table->string('partner_taxid')->nullable()->index();
            //A partner cég címe (irányítószám, város, utca, ország -- itt nem kell külön mezőkbe rakni)
            $table->string('partner_address')->nullable()->index();
            //a partner e-mail címe. Adatbevitelnél kötelező mező kell, hogy legyen, mert ezen keresztül lehet őt meghívni.
            $table->string('partner_email')->nullable()->index();
            //a partner mobil vagy vezetékes telefonszáma
            $table->string('partner_phone')->nullable()->index();
            //a partnernál a kapcsolati személy
            $table->string('partner_contact')->nullable()->index();
            // fel lett-e dolgozva
            $table->boolean('is_compensed')->default(false)->index();

            $table->string('status', 20)->default(CompensationItemStatus::FREE->value);

            $table->unique(['invoice_id_for_compensation', 'partner_unique_id'], 'compensationitems_unique_key_1');

            $table->timestamps();
            $table->softDeletes();
        });

        // organizationclaims

        // Schema::create('compensationcollectionheaders', function (Blueprint $table): void {
        //     $table->id();
        //     // A gyűjteményhez kapcsolódó név
        //     $table->string('collection_name')->nullable()->index();
        //     $table->timestamps();
        // });

        // Schema::create('compensationcollectionitems', function (Blueprint $table): void {
        //     $table->id();
        //     /**
        //      * compensation_collection_header_id
        //     */
        //     $table->unsignedBigInteger('compensation_collection_header_id')->nullable();
        //     $table->foreign('compensation_collection_header_id', 'fk_comp_coll_header')
        //           ->references('id')->on('compensationcollectionheaders')
        //           ->onDelete('cascade');
        //     $table->index('compensation_collection_header_id', 'idx_comp_coll_header');
        //     /**
        //      * compensation_item_id
        //     */
        //     $table->unsignedBigInteger('compensation_item_id')->nullable();
        //     $table->foreign('compensation_item_id', 'fk_comp_item')
        //           ->references('id')->on('compensationitems')
        //           ->onDelete('cascade');
        //     $table->index('compensation_item_id', 'idx_comp_item');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensationcollectionitems');
        Schema::dropIfExists('compensationcollectionheaders');
        Schema::dropIfExists('compensationitems');
    }
};
