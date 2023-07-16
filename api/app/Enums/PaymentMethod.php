<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    const CASH = 'CASH';

    const INDIVIDUAL_ACCOUNTING = 'INDIVIDUAL_ACCOUNTING';

    public static function getDescription(mixed $value): string
    {
        if ($value == self::CASH) {
            return '現金';
        }
        if ($value == self::INDIVIDUAL_ACCOUNTING) {
            return '個別会計';
        }

        return parent::getDescription($value);
    }
}
