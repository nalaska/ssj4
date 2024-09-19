<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'administrateur';
    case PROFESOR = 'professeur';
    case STUDENT = 'eleve';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}