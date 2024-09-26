<?php

namespace App\Enums;

enum PostType: string
{
    case Alerte = 'alerte';
    case Post = 'post';
    case Evennement = 'evennement';
}
