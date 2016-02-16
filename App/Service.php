<?php

namespace App;

use App\Exception\ImplementException;

class Service
{

    /**
     * @var array
     */
    protected static $instanceSet = [];

    /**
     * @var array
     */
    protected static $builderSet = [];

    //region BASE *************************************************

    /**
     * @param $source
     *
     * @return bool
     * @throws Exception\RuntimeException
     */
    public static function init($source)
    {

        if (is_array($source)) {
            self::$builderSet = $source;

            return true;
        }

        if (file_exists($source)) {
            self::$builderSet = require_once $source;

            return true;
        }


        throw new \App\Exception\RuntimeException(__CLASS__ . " can't load source");
    }

    //endregion *******************************************

    //region MAIN *****************************************

    /**
     * @param $key
     *
     * @return mixed
     * @throws \App\Exception\RuntimeException
     */
    public static function get($key)
    {

        if (!array_key_exists($key, self::$instanceSet)) {
            self::$instanceSet[$key] = self::build($key);
        }

        return self::$instanceSet[$key];

    }

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            'instanceSet' => array_keys(self::$instanceSet),
            'builderSet'  => array_keys(self::$builderSet)
        ];
    }


    /**
     * @param $key
     *
     * @return mixed
     * @throws \App\Exception\RuntimeException
     */
    public static function build($key)
    {

        if (!array_key_exists($key, self::$builderSet)) {
            throw new \App\Exception\RuntimeException(__CLASS__ . " builder is undefined for '{$key}'");
        }

        if (!is_callable(self::$builderSet[$key])) {
            throw new \App\Exception\RuntimeException(__CLASS__ . " builder is not callable for '{$key}'");
        }

        $function = self::$builderSet[$key];

        return $function();
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$builderSet[$key] = $value;
    }

    //endregion ***************************************************


}