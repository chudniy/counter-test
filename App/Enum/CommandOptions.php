<?php

namespace App\Enum;

enum CommandOptions
{
    case Action;
    case File;

    public function getLongName(): string
    {
        return strtolower($this->name);
    }

    public function getShortName(): string
    {
        return substr($this->name, 0, 1);
    }

    public static function getShortOptionsString(): string
    {
        $shortValuesString = '';
        foreach (self::cases() as $option) {
            $shortValuesString .= $option->getShortName() . ':';
        }

        return $shortValuesString;
    }

    public static function getLongOptionsArray(): array
    {
        $shortValuesString = [];
        foreach (self::cases() as $option) {
            $shortValuesString[] = $option->getLongName() . ':';
        }

        return $shortValuesString;
    }
}