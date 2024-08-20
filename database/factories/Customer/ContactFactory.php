<?php

declare(strict_types=1);

namespace Database\Factories\Customer;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Factories\Base\BaseFactory;
use Domain\User\Services\RoleService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class ContactFactory extends BaseFactory
{
    protected $model = Contact::class;

    protected static $contactModel;

    protected static $contactProfileModel;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected static function getContactModel()
    {
        if (! self::$contactModel) {
            self::$contactModel = new Contact;
        }

        return self::$contactModel;
    }

    protected static function getContactProfileModel()
    {
        if (! self::$contactProfileModel) {
            self::$contactProfileModel = new ContactProfile;
        }

        return self::$contactProfileModel;
    }

    /**
     * Create a new model instance in the database if a duplicate entry error occurs.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function createUntilNotTaken(array $attributes = [], $doNotTouchTheseProps = [], $debug = false)
    {
        $contact = null;
        if (empty($attributes)) {
            $contact = new Contact;
            $contact->password = self::createPassword();
            $contact->fillEmailVerifiedAtWithCurrentTime();
            $contact->save();
            $contact->refresh();
            $contactProfile = ContactProfile::factory()->createUntilNotTaken();
            $contactProfile->setContact($contact);
            $contactProfile->save();
            $contactProfile->refresh();
        } else {
            $contactProfileData = [];
            foreach (self::getContactProfileModel()->getFillable() as $fillable) {
                if (isset($attributes[$fillable])) {
                    $contactProfileData[$fillable] = $attributes[$fillable];
                }
            }

            $contactData = [];
            foreach (self::getContactModel()->getFillable() as $fillable) {
                if (isset($attributes[$fillable])) {
                    $contactData[$fillable] = $attributes[$fillable];
                }
            }

            $contact = parent::createUntilNotTaken($contactData);
            $contactProfile = ContactProfileFactory::new()->createUntilNotTaken($contactProfileData);
            if ($contactProfile instanceof ContactProfile) {
                $contactProfile->contact()->associate($contact);
                $contactProfile->save();
            }
        }

        if ($contact instanceof Contact) {
            $contact->syncRoles([RoleService::ROLE_REGISTERED_CUSTOMER]);
        }

        return $contact;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_verified_at' => now(),
            'password' => self::createPassword(),
            'remember_token' => Str::random(16),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public static function createPassword()
    {
        return self::$password ??= Hash::make('password');
    }
}
