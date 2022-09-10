<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Part extends Enum
{
    const SOPRANO = 'S';

    const ALTO = 'A';

    const TENOR = 'T';

    const BASS = 'B';

    public static function getDescriptionAbbrev($value): string
    {
        if ($value == self::SOPRANO) {
            return 'Sop.';
        }
        if ($value == self::ALTO) {
            return 'Alt.';
        }
        if ($value == self::TENOR) {
            return 'Ten.';
        }
        if ($value == self::BASS) {
            return 'Bas.';
        }

        return parent::getDescription($value);
    }

    public static function getDescriptionJp($value): string
    {
        if ($value == self::SOPRANO) {
            return 'ソプラノ';
        }
        if ($value == self::ALTO) {
            return 'アルト';
        }
        if ($value == self::TENOR) {
            return 'テナー';
        }
        if ($value == self::BASS) {
            return 'ベース';
        }

        return parent::getDescription($value);
    }
}
