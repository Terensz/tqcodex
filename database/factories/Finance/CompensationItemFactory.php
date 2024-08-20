<?php

declare(strict_types=1);

namespace Database\Factories\Finance;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Customer\Models\Organization;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Factories\Base\BaseFactory;
use Domain\Shared\Helpers\StringHelper;

final class CompensationItemFactory extends BaseFactory
{
    protected $model = CompensationItem::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $contact = Contact::factory()->createUntilNotTaken();

        // dump($contact->getContactProfile());
        // dump($contact);
        // exit;

        $organization = Organization::factory()->create();

        $contactProfileOrganization = new ContactProfileOrganization;
        $contactProfileOrganization->contact_profile_id = $contact->getContactProfile()->id;
        $contactProfileOrganization->organization_id = $organization->id;
        $contactProfileOrganization->save();

        $partnerOrganization = Organization::factory()->create();

        $partnerContact = Contact::factory()->createUntilNotTaken();

        $partnerContactProfileOrganization = new ContactProfileOrganization;
        $partnerContactProfileOrganization->contact_profile_id = $partnerContact->getContactProfile()->id;
        $partnerContactProfileOrganization->organization_id = $partnerOrganization->id;
        $partnerContactProfileOrganization->save();

        // dump($organization);
        // exit;

        $invoice_date = fake()->dateTimeBetween('-2 week', '-1 week');

        $due_date = fake()->dateTimeBetween($invoice_date, '+1 week');

        $compensationItem = [
            'contact_id' => $contact->id,
            'organization_id' => $organization->id,
            'invoice_id_for_compensation' => StringHelper::generateRandomString(12),
            'due_date' => $due_date,
            'invoice_date' => $invoice_date,
            'fulfilment_date' => null,
            'invoice_amount' => round(fake()->numberBetween(12000, 2100000), -3),
            'invoice_type' => fake()->randomElement(InvoiceType::getValueArray()),
            'payment_mode' => fake()->randomElement(PaymentMode::getValueArray()),
            'currency' => Currency::HUF->value,
            'partner_org_id' => $partnerOrganization->id,
            'partner_unique_id' => $partnerOrganization->taxid,
            'partner_name' => $partnerOrganization->name,
            // ''
        ];

        // dump($compensationItem); exit;

        return $compensationItem;
    }
}
