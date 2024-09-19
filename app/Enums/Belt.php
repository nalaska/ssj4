<?php

namespace App\Enums;

enum Belt : string
{
    case WHITE = 'blanche';
    
    // Children's belts
    case GRAY = 'grise';
    case YELLOW = 'jaune';
    case ORANGE = 'orange';
    case GREEN = 'verte';

    // Adult belts
    case BLUE = 'bleu';
    case PURPLE = 'violette';
    case BROWN = 'marron';
    case BLACK = 'noire';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
