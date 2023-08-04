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
}
