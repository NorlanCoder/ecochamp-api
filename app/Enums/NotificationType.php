<?php

namespace App\Enums;

enum NotificationType: string
{
    case Comment = 'commented';
    case Love = 'love';
    case Share = 'shared';
    case Action = 'action';
}
