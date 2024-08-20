<?php

namespace Domain\Shared\Rules;

use Closure;
use Domain\Shared\Helpers\PHPHelper;
use Domain\Shared\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEnum implements ValidationRule
{
    public $enum;

    public $translationFile;

    public $valueWrapper = '"';

    public function __construct($enum, $translationFile = null)
    {
        // $this->roleType = $roleType;
        $this->enum = $enum;
        $this->translationFile = $translationFile;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cases = [];
        $displayedCases = [];

        /**
         * Adding $translationFile is not necessary.
         * If this data is not given, the natural values of the Enum will return.
         */
        if ($this->translationFile) {
            $cases = $this->enum::asSelectArray(false);
            $displayedCases = array_map(fn ($rawCase) => StringHelper::wrapString($rawCase, $this->valueWrapper), $cases);
        } else {
            $cases = array_map(fn ($caseObject) => $caseObject->value, $this->enum::cases());
            $displayedCases = array_map(fn ($caseObject) => StringHelper::wrapString($caseObject->value, $this->valueWrapper), $this->enum::cases());
        }

        if (! empty($value) && ! in_array($value, $cases)) {
            $message = __('validation.NotInPossibleCases', ['value' => $value, 'possibleCases' => PHPHelper::implode(', ', $displayedCases)]);
            $fail($message);
        }
    }

    // public function validate(string $attribute, mixed $value, Closure $fail): void
    // {
    //     $cases = [];

    //     if ($this->translationFile) {
    //         $rawCases = $this->enum::asSelectArray();
    //         $cases = array_map(fn($rawCase) => StringHelper::wrapString($rawCase, $this->valueWrapper), $rawCases);
    //     } else {
    //         $cases = array_map(fn($caseObject) => StringHelper::wrapString($caseObject->value, $this->valueWrapper), $this->enum::cases());
    //     }

    //     $caseValues = array_map(fn($caseObject) => $caseObject->value, $this->enum::cases());
    //     $cases = array_merge($cases, $caseValues);

    //     if (!in_array($value, $cases)) {
    //         $message = __('validation.NotInPossibleCases', ['value' => $value, 'possibleCases' => PHPHelper::implode(', ', $cases)]);
    //         $fail($message);
    //     }
    // }

    // public function validate_V1(string $attribute, mixed $value, Closure $fail): void
    // {
    //     $rawCases = [];
    //     $cases = [];
    //     $displayedCases = [];
    //     if ($this->translationFile) {
    //         $rawCases = $this->enum::asSelectArray();
    //         foreach ($rawCases as $rawCase) {
    //             $cases[] = StringHelper::wrapString($rawCase, $this->valueWrapper);
    //         }
    //     } else {
    //         foreach ($this->enum::cases() as $caseObject) {
    //             $cases[] = StringHelper::wrapString($caseObject->value, $this->valueWrapper);
    //         }
    //     }

    //     foreach ($this->enum::cases() as $caseObject) {
    //         $cases[] = $caseObject->value;
    //     }

    //     if (! in_array($value, $cases)) {
    //         $message = __('validation.NotInPossibleCases', ['value' => $value, 'possibleCases' => PHPHelper::implode(', ', $cases)]);
    //         // dump($message);
    //         $fail($message);
    //     }
    // }
}
