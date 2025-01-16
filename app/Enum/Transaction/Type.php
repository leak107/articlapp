<?php

namespace App\Enum\Transaction;

enum Type: string
{
    case IN = 'in';
    case OUT = 'out';
    case EXPIRED = 'expired';
    case BROKEN = 'broken';
    case OTHERS = 'others';

    /**
    * @return array
    */
    public static function getLists(): array
    {
        return [
            self::IN,
            self::OUT,
            self::EXPIRED,
            self::BROKEN,
            self::OTHERS
        ];
    }
}
