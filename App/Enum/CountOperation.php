<?php

namespace App\Enum;

enum CountOperation
{
    case Plus;
    case Minus;
    case Multiply;
    case Division;

    public function getName(): string
    {
        return strtolower($this->name);
    }
}