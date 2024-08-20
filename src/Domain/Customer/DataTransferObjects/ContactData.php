<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LaravelData\Data;

/**
 * Contact data object used in most service forms
 */
class ContactData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $referred_by,
        public readonly string $lastname,
        public readonly string $firstname,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $business_type,
        public readonly bool $terms_ok,
        public readonly ?bool $news_ok,
        public readonly ?bool $direct_sales_ok
    ) {}

    public static function fromRequest(Request $request): self
    {
        $phone = (string) new PhoneNumber(strval($request->phone), strval($request->phone_country));

        return self::from([
            'id' => $request->id ?? null,
            'referred_by' => $request->referred_by ?? null,
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'phone' => $phone,
            'email' => $request->email,
            'business_type' => $request->business_type,
            'terms_ok' => $request->boolean('terms_ok'),
            'news_ok' => $request->boolean('news_ok'),
            'direct_sales_ok' => $request->boolean('direct_sales_ok'),
        ]);
    }

    public static function fromModel(Contact $contact): self
    {
        return self::from([
            'id' => $contact->id,
            'referred_by' => $contact->referred_by,
            'lastname' => $contact->getContactProfile() ? $contact->getContactProfile()->lastname : null,
            'firstname' => $contact->getContactProfile() ? $contact->getContactProfile()->firstname : null,
            'phone' => $contact->getContactProfile() ? $contact->getContactProfile()->phone : null,
            'email' => $contact->getContactProfile() ? $contact->getContactProfile()->email : null,
            'business_type' => $contact->business_type,
            'terms_ok' => $contact->terms_ok,
            'news_ok' => $contact->news_ok,
            'direct_sales_ok' => $contact->direct_sales_ok,
        ]);
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->setRules(self::rules());
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return \Domain\Customer\Rules\ContactRules::rules();
    }
}
