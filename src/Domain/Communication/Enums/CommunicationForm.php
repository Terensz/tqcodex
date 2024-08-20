<?php

namespace Domain\Communication\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CommunicationForm: string
{
    use EnumToArray;

    case EMAIL = 'Email';

    case SMS = 'SMS';

    public function label(): string
    {
        return match ($this) {
            self::EMAIL => __('communication.Email'),
            self::SMS => __('communication.SMS'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
