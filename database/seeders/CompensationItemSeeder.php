<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Domain\Customer\Enums\Location;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\Organization;
use Domain\Customer\Services\OrganizationSeederService;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Domain\Finance\Models\CompensationItem;
use Domain\Project\Services\CompensationItemSeederService;
use Illuminate\Database\Seeder;

final class CompensationItemSeeder extends Seeder
{
    protected int $count;

    public function __construct(int $count = 100)
    {
        $this->count = $count;
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $contactProfile = ContactProfile::where(['email' => 'terencecleric@gmail.com'])->first();
        $user = $contactProfile->getContact();

        $org = OrganizationSeederService::getNthOrgOfContact(2, 0);
        $invoiceAmount = 135270;
        $partnerName = 'Teszt Nyrt.';
        $partnerContactName = 'Kiss János';
        $partnerEmail = 'kissjano999@gmail.com';

        $compensationItem = new CompensationItem([
            'contact_id' => $user->id,
            'organization_id' => $org instanceof Organization ? $org->id : null,
            'invoice_id_for_compensation' => 'TEST000000001',
            'due_date' => Carbon::parse('2026-01-01 10:00:00'),
            'invoice_date' => Carbon::parse('2024-06-01 18:00:00'),
            'fulfilment_date' => null,
            'invoice_amount' => $invoiceAmount,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'partner_org_id' => null,
            'partner_location' => Location::HU->value,
            'partner_taxpayer_id' => '12345611-1-42',
            'partner_name' => $partnerName,
            'partner_unique_id' => $partnerName,
            'partner_contact' => $partnerContactName,
            'partner_email' => $partnerEmail,
        ]);
        $compensationItem->save();

        $org = OrganizationSeederService::getNthOrgOfContact(2, 1);
        $invoiceAmount = 274500;
        $partnerName = 'Próba Nyrt.';
        $partnerContactName = 'Kovács Lajos';
        $partnerEmail = 'kovacslajos111@gmail.com';

        $compensationItem = new CompensationItem([
            'contact_id' => $user->id,
            'organization_id' => $org instanceof Organization ? $org->id : null,
            'invoice_id_for_compensation' => 'TEST000000002',
            'due_date' => Carbon::parse('2026-02-12 12:00:00'),
            'invoice_date' => Carbon::parse('2024-07-04 13:00:00'),
            'fulfilment_date' => null,
            'invoice_amount' => $invoiceAmount,
            'invoice_type' => InvoiceType::DEBT->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'partner_org_id' => null,
            'partner_location' => Location::HU->value,
            'partner_taxpayer_id' => '12345612-1-42',
            'partner_name' => $partnerName,
            'partner_unique_id' => $partnerName,
            'partner_contact' => $partnerContactName,
            'partner_email' => $partnerEmail,
        ]);
        $compensationItem->save();

        $compensationItemSeederService = new CompensationItemSeederService;
        $compensationItemSeederService->count = $this->count;
        $compensationItemSeederService->seedCommunicationForInvites = false;
        $compensationItemSeederService->seed();
        // CompensationItem::factory($this->count)->create();
    }
}
