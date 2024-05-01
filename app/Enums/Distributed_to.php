<?php

namespace App\Enums;

enum Distributed_to: string
{
    case ALL = 'all';
    case PEOPLE = 'people';
    case FOLLOWERS = 'followers';
}
