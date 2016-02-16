<?php

namespace App;

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
        static::$config = array_merge([], static::$config, (array) $array);
    }

    //endregion *******************************************

    //region MAIN *******************************************

    /**
     * @param      $key
     * @param null $default
     *
     * @return null
     */
    public static function get($key, $default = null)
    {
        return (self::exists($key))
            ? self::$config[$key]
            : $default;
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return self::$config;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set($key, $value)
    {
        return self::$config[$key] = $value;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function add($key, $value)
    {
        array_push($key, $value);

        return $value;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function exists($key)
    {
        return array_key_exists($key, self::$config);
    }

    //endregion *******************************************
}