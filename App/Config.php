<?php

namespace App;

use Lib\ArrayHelper;

class Config
{
    /**
     * @var array
     */
    protected static $config = [];

    //region BASE *******************************************

    /**
     * @param array $array
     */
    public static function extend(array $array = [])
    {
        static::$config = array_merge([], static::$config, (array)$array);
    }

    /**
     *
     */
    public static function clear()
    {
        static::$config = [];
    }

    //endregion *******************************************

    //region MAIN *******************************************

    /**
     * @param      $key
     * @param null $default
     *
     * @return null
     */
    public static function get($key = null, $default = null)
    {
        if ($key === null) {
            return self::$config;
        }

        return ArrayHelper::getByPath(self::$config, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set($key, $value)
    {
        return ArrayHelper::setByPath(self::$config, $key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function add($key, $value)
    {
        return ArrayHelper::pushByPath(self::$config, $key, $value);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function exists($key)
    {
        return ArrayHelper::existsByPath(self::$config, $key);
    }

    //endregion *******************************************
}