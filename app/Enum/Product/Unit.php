<?php

namespace App\Enum\Product;

enum Unit: string
{
    case PIECES = 'pcs';
    case MILLILITER = 'milliliter';
    case LITER = 'liter';
    case KILOGRAM = 'kilogram';
    case GRAM = 'gram';
    case TON = 'ton';

    /**
    * @return array
    */
    public static function getLists(): array
    {
        return [
            self::PIECES,
            self::MILLILITER,
            self::LITER,
            self::KILOGRAM,
            self::GRAM,
            self::TON,
        ];
    }
}
