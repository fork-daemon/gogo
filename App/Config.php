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
     * @use :
     *      Config::load('config-1.php');
     *      Config::load(['config-1.php', 'config-2.php']);
     *
     * @param array|string $files
     */
    public static function load($files)
    {
        $files = (array) $files;

        foreach ($files as $file) {
            if (is_file($file)) {
                $array = require_once($file);
                static::extend($array);
            }
        }
    }

    /**
     *
     */
    public static function clean()
    {
        static::$config = [];
    }

    /**
     * @use :
     *      Config::extend(['mysql' => ['host' => 'localhost']]);
     *
     * @param array $array
     */
    public static function extend(array $array = [])
    {
        static::$config = array_merge([], static::$config, $array);
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