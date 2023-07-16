<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BulletinBoardStatus extends Enum
{
    const RELEASE = 'RELEASE';

    const DRAFT = 'DRAFT';

    public static function getDescription($value): string
    {
        if ($value == self::RELEASE) {
            return '公開';
        }
        if ($value == self::DRAFT) {
            return '下書き';
        }

        return parent::getDescription($value);
    }
}
