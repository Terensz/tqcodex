<?php

declare(strict_types=1);

namespace Domain\Finance\Models;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactOrg;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Customer\Models\Organization;
use Domain\Finance\Builders\CompensationItemBuilder;
use Domain\Shared\Models\BaseModel;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/**
 * CompensationItem
 */
final class CompensationItem extends BaseModel
{
    use SoftDeletes;

    private $stringModelAssignatures = [
        'contactEmail' => [
            'bindingPoint' => 'contact_id',
            'models' => [],
        ],
        'organizationName' => [
            'bindingPoint' => 'organization_id',
            'models' => [],
        ],
    ];

    public $technicalProperties = [
        'contactEmail',
        'organizationName',
    ];

    public $contactEmail;

    public $organizationName;

    // public $partnerOrganizationName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'id',
        'contactEmail',
        'organizationName',
        'contact_id',
        'organization_id',
        'invoice_id_for_compensation',
        'invoice_internal_id',
        'due_date',
        'invoice_date',
        'fulfilment_date',
        'late_interest_rate',
        'late_interest_amount',
        'invoice_amount',
        'invoice_type',
        'payment_mode',
        'currency',
        'description',
        'is_part_amount',
        'is_disputed',
        'partner_unique_id',
        'partner_org_id',
        'partner_location',
        'partner_name',
        'partner_taxpayer_id',
        'partner_eutaxid',
        'partner_taxid',
        'partner_address',
        'partner_email',
        'partner_phone',
        'partner_contact',
        'is_compensed',
    ];

    protected $appends = ['contactEmail', 'organizationName'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'compensationitems';

    public function newEloquentBuilder($query): CompensationItemBuilder
    {
        return new CompensationItemBuilder($query);
    }

    public function __construct(array $attributes = [])
    {
        $this->attributes['contactEmail'] = null;

        $this->attributes['organizationName'] = null;

        $this->bootIfNotBooted();

        $this->initializeTraits();

        $this->syncOriginal();

        if (! isset($attributes['partner_unique_id']) || empty($attributes['partner_unique_id'])) {
            $attributes['partner_unique_id'] = null;
        }
        if (! isset($attributes['partner_email']) || empty($attributes['partner_email'])) {
            $attributes['partner_email'] = null;
        }
        if (isset($attributes['contactEmail'])) {
            $this->setContactEmailAttribute($attributes['contactEmail']);
        }
        if (isset($attributes['organizationName'])) {
            $this->setOrganizationNameAttribute($attributes['organizationName']);
        }

        if (array_key_exists('contactEmail', $attributes)) {
            unset($attributes['contactEmail']);
        }
        if (array_key_exists('organizationName', $attributes)) {
            unset($attributes['organizationName']);
        }

        $this->fill($attributes);

        $this->setPartnerUniqueId();
        // dump($this);exit;
    }

    public function setPartnerUniqueId()
    {
        // dump();
        if ($this->partner_unique_id) {
            return;
        }

        $partnerUniqueId = null;
        if ($this->partner_org_id) {
            $partnerOrg = $this->getPartnerOrg();
            if (! $partnerOrg) {
                Log::debug('src/Domain/Finance/Models/CompensationItem.php : setPartnerUniqueId()'."\n".print_r($this, true));
                exit;
            }
            $partnerUniqueId = $partnerOrg->taxpayer_id;
        }
        if (! $partnerUniqueId && $this->partner_taxpayer_id) {
            $partnerUniqueId = $this->partner_taxpayer_id;
        }
        if (! $partnerUniqueId && $this->partner_name) {
            $partnerUniqueId = $this->partner_name;
        }

        $this->partner_unique_id = $partnerUniqueId;
    }

    /**
     * Get the Contact belongs to this personal email address
     *
     * @return BelongsTo<Contact, CompensationItem>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
    // public function contactProfile(): BelongsTo
    // {
    //     return $this->belongsTo(ContactProfile::class);
    // }

    /**
     * @return BelongsTo<RecorderOrg, CompensationItem>
     */
    public function recorderOrg(): BelongsTo
    {
        return $this->belongsTo(RecorderOrg::class, 'organization_id', 'id');
    }

    public function getRecorderOrg()
    {
        return $this->recorderOrg()->first();
    }

    /**
     * @return BelongsTo<PartnerOrg, CompensationItem>
     */
    public function partnerOrg(): BelongsTo
    {
        return $this->belongsTo(PartnerOrg::class, 'partner_org_id', 'id');
    }

    public function getPartnerOrg()
    {
        return $this->partnerOrg()->first();
    }

    public function getStringModelAssignatures()
    {
        return $this->stringModelAssignatures;
    }

    /**
     * stringModelAssignatures: contactEmail
     */
    public function setContactEmailAttribute($contactEmail)
    {
        $this->attributes['contactEmail'] = $contactEmail;
        $validatedContact = $this->validateContactByEmail($contactEmail);
        if ($validatedContact['result'] && $validatedContact['contact']) {
            $this->contact_id = $validatedContact['contact']->id;
        }
    }

    public function getAssignableContactByEmail($contactEmail): ?Contact
    {
        $contactEmail = trim($contactEmail);
        /**
         * Preventing multiple queries for the same search.
         */
        if (isset($this->stringModelAssignatures['contactEmail']['models'][$contactEmail])) {
            return $this->stringModelAssignatures['contactEmail']['models'][$contactEmail];
        }
        $contact = null;
        $contactProfile = ContactProfile::where(['email' => $contactEmail])->first();
        $currentContact = UserService::getContact();
        if ($contactProfile && $contactProfile->getContact() && $currentContact && $contactProfile->getContact()->id === $currentContact->id) {
            $contact = $contactProfile->getContact();
        }

        $this->stringModelAssignatures['contactEmail']['models'][$contactEmail] = $contact;

        return $contact;
    }

    public function validateContactByEmail($contactEmail): array
    {
        $contact = $this->getAssignableContactByEmail($contactEmail);

        return [
            'result' => $contact ? true : false,
            'contact' => $contact,
        ];
    }

    public function getContactEmailAttribute()
    {
        return $this->contactEmail;
    }

    /**
     * stringModelAssignatures: organizationName
     */
    public function setOrganizationNameAttribute($organizationName)
    {
        $this->attributes['organizationName'] = $organizationName;
        $validatedOrganization = $this->validateOrganizationByName($organizationName);
        // dump($validatedOrganization);
        if ($validatedOrganization['result'] && $validatedOrganization['organization']) {
            $this->organization_id = $validatedOrganization['organization']->id;
        }
    }

    public function getAssignableOrganizationByName($organizationName): ?Organization
    {

        $organizationName = trim($organizationName);
        /**
         * Preventing multiple queries for the same search.
         */
        if (isset($this->stringModelAssignatures['organizationName']['models'][$organizationName])) {
            return $this->stringModelAssignatures['organizationName']['models'][$organizationName];
        }
        $organization = null;
        $searchedOrganization = Organization::where(['name' => trim($organizationName)])->first();
        $currentContactProfile = UserService::getContactProfile();

        if ($currentContactProfile && $searchedOrganization) {
            $contactProfileOrganizations = ContactProfileOrganization::where(['contact_profile_id' => $currentContactProfile->id])->get();
            foreach ($contactProfileOrganizations as $contactProfileOrganization) {
                $loopedOrganization = $contactProfileOrganization->organization()->first();
                // if ($loopedOrganization && ($loopedOrganization->id === $searchedOrganization->id)) {
                if ($loopedOrganization && $loopedOrganization instanceof Organization && ($loopedOrganization->id === $searchedOrganization->id)) {
                    $organization = $searchedOrganization;
                }
            }
        }

        $this->stringModelAssignatures['organizationName']['models'][$organizationName] = $organization;

        return $organization;
    }

    public function validateOrganizationByName($organizationName): array
    {
        $organization = $this->getAssignableOrganizationByName($organizationName);

        return [
            'result' => $organization ? true : false,
            'organization' => $organization,
        ];
    }

    public function getOrganizationNameAttribute()
    {
        return $this->organizationName;
    }

    public function setPartnerOrgIdAttribute($partner_org_id)
    {
        $this->attributes['partner_org_id'] = $partner_org_id;
    }

    public function setPartnerNameAttribute($partner_name)
    {
        $this->attributes['partner_name'] = $partner_name;

        if (! empty($partner_name)) {
            $searchedOrganization = ContactOrg::where(['name' => trim($partner_name)])->first();
            if ($searchedOrganization && $searchedOrganization instanceof ContactOrg) {
                $validPartnerOrg = PartnerOrg::potentialPartner()->find($searchedOrganization->id);
                $this->partner_org_id = $validPartnerOrg ? $validPartnerOrg->id : null;
            }
        }

        // if ($partner_name == 'NAPCSILLAG KFT.') {
        //     dump(ContactOrg::potentialPartner()->get());
        //     dump($validPartnerOrg);
        //     dump($searchedOrganization);
        //     dump($this);exit;
        // }
    }

    public function save(array $options = [])
    {
        $technicalProperties = [];

        // Removing the technical properties until saving.
        foreach ($this->technicalProperties as $technicalProperty) {
            if (array_key_exists($technicalProperty, $this->attributes)) {
                $technicalProperties[$technicalProperty] = $this->attributes[$technicalProperty];
                unset($this->attributes[$technicalProperty]);
            }
        }

        $this->setPartnerUniqueId();

        if (! $this->partner_unique_id) {
            $this->setPartnerUniqueId();
        }

        if (! $this->partner_unique_id) {
            Log::debug('src/Domain/Finance/Models/CompensationItem.php : save()'."\n".print_r($this, true));
            exit;
        }

        // Saving.
        $result = parent::save($options);

        // Restoring the technical properties.
        foreach ($technicalProperties as $technicalProperty => $value) {
            $this->attributes[$technicalProperty] = $value;
        }

        return $result;
    }

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->technicalProperties as $technicalProperty) {
            if (isset($this->attributes[$technicalProperty])) {
                $array[$technicalProperty] = $this->attributes[$technicalProperty];
            }
        }

        return $array;
    }
}
