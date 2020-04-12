<?php

namespace App\fixtures\Faker\Provider;

use Faker\Provider\Base;

class CustomerProvider extends Base
{
    const COMPAGNY_PROVIDER = [
        'Samsung',
        'Nokia',
        'Siemens',
        'Stark Industries'
    ];


    public static function compagny()
    {
        return self::randomElement(self::COMPAGNY_PROVIDER);
    }

}