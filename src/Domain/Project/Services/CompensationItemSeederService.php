<?php

namespace Domain\Project\Services;

use Domain\Customer\Enums\Location;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\Organization;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Helpers\StringHelper;
use Exception;

class CompensationItemSeederService
{
    public const PARTNER_POOL_REGISTERED_ONLY = 'RegisteredOnly';

    public const PARTNER_POOL_NOT_REGISTERED_ONLY = 'NotRegisteredOnly';

    public const PARTNER_POOL_RANDOM = 'Random';

    public $count = 100;

    public $partnerPool = self::PARTNER_POOL_RANDOM;

    public $pickContactMaxAttempts = 10;

    public $useExistingContacts = true;

    public $contactsData = [];

    public $seedCommunicationForInvites = true;

    public function __construct() {}

    public function getContacts()
    {
        return Contact::get();
    }

    /**
     * The creator person of a Compensation Item
     */
    public function pickRandomCreatorContact(int $counter = 0): Contact
    {
        // Pick a random contact from $this->contactsData
        $randomIndex = array_rand($this->contactsData);

        if (empty($this->contactsData[$randomIndex]['organizations'])) {
            if ($counter > $this->pickContactMaxAttempts) {
                // dump($randomIndex);
                // dump(array_keys($this->contactsData));
                // // dump($this->contactsData);
                // exit;
                throw new Exception('Max attempts to make a creator contact having an org exceeded the limit ('.$this->pickContactMaxAttempts.')');
            }
            $counter++;

            return $this->pickRandomCreatorContact($counter);
        }

        return $this->contactsData[$randomIndex]['contactObject'];
    }

    /**
     * The creator person of a Compensation Item
     */
    public function pickRandomOrg(Contact $contact): ?Organization
    {
        foreach ($this->contactsData as $contactData) {
            if ($contactData['id'] === $contact->id) {
                $organizations = $contactData['organizations'];
                if (! empty($organizations)) {
                    $randomIndex = array_rand($organizations);

                    return $organizations[$randomIndex];
                }
            }
        }

        return null;
    }

    /**
     * The creator person of a Compensation Item
     */
    public function pickRandomPartnerContact(Contact $creatorContact, int $counter = 0): Contact
    {
        // Filter contacts that are not in the same organizations as $creatorContact
        $filteredContacts = array_filter($this->contactsData, function ($contactData) use ($creatorContact) {
            // Check if the contact is in different organizations than the creator contact
            foreach ($contactData['organizations'] as $organization) {
                $creatorOrgs = $creatorContact->getOrganizations();
                foreach ($creatorOrgs as $creatorOrg) {
                    if ($organization['id'] === $creatorOrg['id']) {
                        return false;
                    }
                }
            }

            /**
             * We also don't want to return a contact having no orgs at all.
             * But if we have, than we put that into the filtered pool.
             */
            return empty($contactData['organizations']) ? false : true;
        });

        // Pick a random partner contact from the filtered list
        $randomIndex = array_rand($filteredContacts);

        // Return the Contact object of the randomly picked partner
        return $filteredContacts[$randomIndex]['contactObject'];
    }

    public function seed()
    {
        // dump('seed@CompensationItemSeederService');
        if (empty($this->contactsData) && $this->useExistingContacts) {
            foreach ($this->getContacts() as $contactObject) {
                $contactProfile = $contactObject->getContactProfile();
                if ($contactProfile) {
                    $organizations = $contactObject->getOrganizations();

                    if ($organizations) {
                        $this->contactsData[] = [
                            'id' => $contactObject->id,
                            'contactProfileId' => $contactProfile->id,
                            'contactObject' => $contactObject,
                            'organizations' => $organizations,
                        ];
                    }
                }
            }
        }

        for ($i = 0; $i < $this->count; $i++) {
            $creatorContact = $this->pickRandomCreatorContact();
            $creatorOrg = $this->pickRandomOrg($creatorContact);

            $invoice_date = fake()->dateTimeBetween('-2 week', '-1 week');
            $due_date = fake()->dateTimeBetween($invoice_date, '+1 week');

            $compensationItemData = [
                'contact_id' => $creatorContact->id,
                'organization_id' => $creatorOrg->id,
                'invoice_id_for_compensation' => StringHelper::generateRandomString(12),
                'due_date' => $due_date,
                'invoice_date' => $invoice_date,
                'fulfilment_date' => null,
                'invoice_amount' => round(fake()->numberBetween(12000, 2100000), -3),
                'invoice_type' => fake()->randomElement(InvoiceType::getValueArray()),
                'payment_mode' => fake()->randomElement(PaymentMode::getValueArray()),
                'currency' => Currency::HUF->value,
                // ''
            ];

            $randomBoolean = rand(0, 1) === 1;
            /**
             * We put some randomness here, if randomness is required.
             */
            if (in_array($this->partnerPool, [self::PARTNER_POOL_REGISTERED_ONLY, self::PARTNER_POOL_RANDOM])) {

                /**
                 * We pick a registered contact for partner
                 */
                if ($this->partnerPool === self::PARTNER_POOL_REGISTERED_ONLY || $this->partnerPool === self::PARTNER_POOL_RANDOM && $randomBoolean) {
                    $partnerContact = $this->pickRandomPartnerContact($creatorContact);
                    $partnerOrg = $this->pickRandomOrg($partnerContact);
                    $compensationItemData = array_merge($compensationItemData, [
                        'partner_org_id' => $partnerOrg->id,
                        'partner_unique_id' => $partnerOrg->taxid,
                        'partner_name' => $partnerOrg->name,
                    ]);
                }
                /**
                 * We will invite a not registered partner.
                 * Q: Will we send emails also in the seeding process? A: we will have a switch for that: $this->seedCommunicationForInvites
                 */
                else {
                    $partnerName = fake()->company.' Nyrt.';
                    $compensationItemData = array_merge($compensationItemData, [
                        'partner_org_id' => null,
                        'partner_location' => Location::HU->value,
                        'partner_taxpayer_id' => fake()->numerify('########').'-1-42',
                        'partner_name' => $partnerName,
                        'partner_unique_id' => $partnerName,
                        'partner_contact' => fake()->firstName().' '.fake()->lastName(),
                        'partner_email' => fake()->email(),
                    ]);
                    if ($this->seedCommunicationForInvites) {
                        /**
                         * @todo: communication seeding
                         */
                    }
                }
            }

            // $compensationItemData = [
            //     'contact_id' => $creatorContact->id,
            //     'organization_id' => $creatorOrg->id,
            //     'invoice_id_for_compensation' => StringHelper::generateRandomString(12),
            //     'due_date' => $due_date,
            //     'invoice_date' => $invoice_date,
            //     'fulfilment_date' => null,
            //     'invoice_amount' => round(fake()->numberBetween(12000, 2100000), -3),
            //     'invoice_type' => fake()->randomElement(InvoiceType::getValueArray()),
            //     'payment_mode' => fake()->randomElement(PaymentMode::getValueArray()),
            //     'currency' => Currency::HUF->value,
            //     'partner_org_id' => $partnerOrg->id,
            //     'partner_unique_id' => $partnerOrg->taxid,
            //     'partner_name' => $partnerOrg->name,
            //     'partner_contact' => $partnerOrg->name,
            //     // ''
            // ];

            $compensationItem = new CompensationItem($compensationItemData);
            $compensationItem->save();
        }
    }
}
