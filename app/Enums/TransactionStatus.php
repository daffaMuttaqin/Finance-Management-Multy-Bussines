<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case POSTED = 'POSTED';
    case VOIDED = 'VOIDED';
}
