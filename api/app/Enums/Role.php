<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Role extends Enum
{
    const MASTER = 'MASTER';

    const MANAGER = 'MANAGER';

    const ACCOUNTANT = 'ACCOUNTANT';

    const CAMP = 'CAMP';

    public static function getDescription($value): string
    {
        if ($value === self::MASTER) {
            return '管理人';
        }
        if ($value === self::MANAGER) {
            return '運営';
        }
        if ($value === self::ACCOUNTANT) {
            return '会計';
        }
        if ($value === self::CAMP) {
            return '合宿委員';
        }
    }
}
