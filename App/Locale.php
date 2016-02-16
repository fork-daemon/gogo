<?php

namespace App;

class Locale
{
    /**
     * @var array
     */
    protected static $locale = [];

    //region BASE *******************************************

    /**
     * @param array $array
     */
    public static function extend(array $array = [])
    {
        static::$locale = array_merge([], static::$locale, (array) $array);
    }

    /**
     *
     */
    public static function clear()
    {
        static::$locale = [];
    }

}