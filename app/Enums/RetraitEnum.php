<?php

namespace App\Enums;

enum RetraitEnum: string
{
    case IN_PROGRESS = 'En attente';
    case ACCEPT = 'Valider';
    case REJECT = 'Rejeter';
}
