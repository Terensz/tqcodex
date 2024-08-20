<?php

namespace Domain\Shared\Rules;

use Closure;
use Domain\Shared\Helpers\PHPHelper;
use Domain\Shared\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBoolString implements ValidationRule
{
    // public $enum;

    // public function __construct($enum)
    // {
    //     // $this->roleType = $roleType;
    //     $this->enum = $enum;
    // }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fail('alma!');
        // $message = __('validation.NotInPossibleCases', ['value' => StringHelper::alterToString($value), 'possibleCases' => PHPHelper::implode(', ', [__('shared.True'), __('shared.False')])]);
        // // dump($message);
        // $fail($message);

        // dump($value);//exit;
        // if (($value === '' || $value === null) || ! ((string)$value === '0' || (string)$value === '1')) {
        //     // dump(['value' => StringHelper::alterToString($value), 'possibleCases' => [__('shared.True'), __('shared.False')]]);exit;
        //     $message = __('validation.NotInPossibleCases', ['value' => StringHelper::alterToString($value), 'possibleCases' => PHPHelper::implode(', ', [__('shared.True'), __('shared.False')])]);
        //     // dump($message);
        //     $fail($message);
        // }
    }
}
