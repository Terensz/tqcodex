<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Communication\Mails\CustomerEmailChangeRequestNotification;
use Domain\Communication\Mails\ResetCustomerPasswordNotification;
use Domain\Communication\Mails\VerifyCustomerRegistration;
use Domain\Customer\Builders\ContactProfileBuilder;
use Domain\Shared\Models\BaseModel;
use Domain\User\Events\EmailChangeNotificationMethodTriggered;
use Domain\User\Events\PasswordResetNotificationMethodTriggered;
use Domain\User\Services\UserService;
// use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * @property Contact $contact
 * @property string $email
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $phone
 * @property string $mobile
 * @property string $position
 * @property string $language
 * @property string $profile_image
 * @property string $social_media
 */
final class ContactProfile extends BaseModel implements CanResetPasswordContract
{
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;

    public const PROPERTY_FIRST_NAME = 'firstname';

    public const PROPERTY_MIDDLE_NAME = 'middlename';

    public const PROPERTY_LAST_NAME = 'lastname';

    public const PROPERTY_PHONE = 'phone';

    public const PROPERTY_MOBILE = 'mobile';

    public const PROPERTY_EMAIL = 'email';

    public const PROPERTY_PROFILE_IMAGE = 'profile_image';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactprofiles';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'title',
        'firstname',
        'middlename',
        'lastname',
        'phone',
        'mobile',
        'position',
        'language',
        'profile_image',
        'social_media',
    ];

    public function getName()
    {
        return $this->lastname.' '.$this->firstname;
    }

    public function newEloquentBuilder($query)
    {
        return new ContactProfileBuilder($query);
    }

    // public function checkPasswordResetToken($token)
    // {
    //     $tokenObject = ContactToken::where(['email' => $this->email, 'token_type' => ContactToken::TOKEN_TYPE_PASSWORD_RESET])->first();
    //     if (! $tokenObject) {
    //         return false;
    //     }
    //     $tokenHash = $tokenObject->token;

    //     return Hash::check($token, $tokenHash);
    // }

    /**
     * Get the Contact belongs to this ip record
     *
     * @return BelongsTo<Contact, ContactProfile>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function setContact(?Contact $contact = null)
    {
        $this->contact_id = $contact && $contact->id ? $contact->id : null;
    }

    public function getContact(): ?Contact
    {
        /** @phpstan-ignore-next-line */
        return $this->contact()->first();
    }

    /**
     * Gets the Contact's addresses
     *
     * @return HasMany<ContactProfileAddress>
     */
    public function contactProfileAddresses(): HasMany
    {
        return $this->hasMany(ContactProfileAddress::class);
    }

    /**
     * Gets the Contact's emails
     *
     * @return HasMany<ContactProfileEmail>
     */
    public function contactProfileEmails(): HasMany
    {
        return $this->hasMany(ContactProfileEmail::class);
    }

    /**
     * Get all of the IP addreses for the Contact.
     *
     * @return HasMany<ContactProfileIp>
     */
    public function contactProfileIps(): HasMany
    {
        return $this->hasMany(ContactProfileIp::class);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyCustomerRegistration);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('customer.password.reset', ['token' => $token]);
        PasswordResetNotificationMethodTriggered::dispatch(UserService::ROLE_TYPE_CUSTOMER, $this, $url);
        $this->notify(new ResetCustomerPasswordNotification($url));
    }

    public function sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail): void
    {
        $url = route('customer.email-change.create', ['token' => $token]);
        EmailChangeNotificationMethodTriggered::dispatch(UserService::ROLE_TYPE_CUSTOMER, $this, $url);
        $this->email = $modifiedEmail;
        $this->save();
        $this->notify(new CustomerEmailChangeRequestNotification($url));
        $this->email = $originalEmail;
        $this->save();
    }

    /**
     * Send the given notification.
     * This methos was copied from the RoutesNotifications trait. ($this->notify(...))
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance)
    {
        /**
         * $instance: Domain\Customer\Notifications\EmailChangeRequestNotification
         * $dispatcher: Illuminate\Notifications\ChannelManager
         */
        $dispatcher = app(Dispatcher::class); // Illuminate\Notifications\ChannelManager
        // dump($instance);
        // dump($dispatcher);exit;
        $dispatcher->send($this, $instance);
    }

    public function getFullName()
    {
        return $this->middlename ? $this->firstname.' '.$this->middlename.' '.$this->lastname : $this->firstname.' '.$this->lastname;
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->getContact() && ! is_null($this->getContact()->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        if (! $this->getContact()) {
            throw new \Exception('Invalid contact profile');
        }

        return $this->getContact()->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
