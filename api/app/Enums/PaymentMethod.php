<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    const CASH = 'CASH';

    const INDIVIDUAL_ACCOUNTING = 'INDIVIDUAL_ACCOUNTING';
}
