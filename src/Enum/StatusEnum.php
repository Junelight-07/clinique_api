<?php

namespace App\Enum;

enum StatusEnum: string
{
    case SCHEDULED = 'programmé';
    case PROGRESS = 'en cours';
    case COMPLETED = 'terminé';
}