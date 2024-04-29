<?php

namespace App\Enums\Enums;

enum Distributed_to: string
{
    case ALL = 'all';
    case PEOPLE = 'people';
    case FOLLOWERS = 'followers';
}
