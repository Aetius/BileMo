<?php


namespace App;


use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class CacheKernel extends HttpCache
{
    //last during 25 days : 2160000
    const MAX_DURATION_CACHE = 10;


    protected function getOptions()
    {
        return [
            "default_ttl" => self::MAX_DURATION_CACHE
        ];
    }
}

