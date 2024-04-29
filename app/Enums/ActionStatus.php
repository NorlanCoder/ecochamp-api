<?php

namespace App\Enums;

enum ActionStatus: string
{
    case IN_PROGRESS = 'in progress';
    case ACCEPT = 'accept';
    case REJECT = 'reject';
}
