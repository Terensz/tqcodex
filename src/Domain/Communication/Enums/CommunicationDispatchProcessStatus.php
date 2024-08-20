<?php

namespace Domain\Communication\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CommunicationDispatchProcessStatus: string
{
    use EnumToArray;

    case IN_PROGRESS = 'InProgress';

    case FINISHED = 'Finished';

    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => __('communication.InProgress'),
            self::FINISHED => __('communication.Finished'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
