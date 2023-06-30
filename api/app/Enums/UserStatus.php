<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const PRESENT = 'PRESENT';

    const ABSENT = 'ABSENT';

    const RESIGNED = 'RESIGNED';

    public static function getDescription($value): string
    {
        if ($value == self::PRESENT) {
            return '在団';
        }
        if ($value == self::ABSENT) {
            return '休団';
        }
        if ($value == self::RESIGNED) {
            return '退団';
        }

        return parent::getDescription($value);
    }
}
