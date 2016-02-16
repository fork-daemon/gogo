<?php

namespace Lib;

class ArrayHelper
{

    const PATH_DIVIDER = '.';

    /**
     * @param array  $data
     * @param string $path
     * @param null   $default
     *
     * @return mixed
     */
    public static function getByPath($data, $path, $default = null)
    {
        $pathKeys = explode(self::PATH_DIVIDER, $path);
        foreach ($pathKeys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return $default;
            }
        }
        return $data;
    }

    /**
     * @param array  $data
     * @param string $path
     *
     * @return bool
     */
    public static function existsByPath($data, $path)
    {
        $pathKeys = explode(self::PATH_DIVIDER, $path);
        foreach ($pathKeys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array  $data
     * @param string $path
     * @param mixed  $value
     * @param bool   $append
     *
     * @return $this
     */
    public static function setByPath(&$data, $path, $value, $append = false)
    {
        $pathKeys = explode(self::PATH_DIVIDER, $path);
        foreach ($pathKeys as $count => $path) {
            if (!isset($data[$path]) && $count != count($pathKeys)) {
                $data[$path] = [];
            }
            $data =& $data[$path];
        }

        if ($append === true) {
            $data[] = $value;
        } else {
            $data = $value;
        }

        return true;
    }

    /**
     * @param array  $data
     * @param string $path
     */
    public static function unsetByPath(&$data, $path)
    {
        $pathKeys = explode(self::PATH_DIVIDER, $path);
        foreach ($pathKeys as $count => $path) {
            if (isset($data[$path]) && $count == count($pathKeys) - 1) {
                unset($data[$path]);
                return;
            } elseif (isset($data[$path])) {
                $data =& $data[$path];
            } else {
                return;
            }
        }
    }

    /**
     * @param $data
     * @param $path
     * @param $value
     *
     * @return bool
     */
    public static function pushByPath(&$data, $path, $value)
    {
        $array = (array) self::getByPath($data, $path);
        $array[] = $value;
        self::setByPath($data, $path, $array);

        return true;
    }

    /**
     * @param $data
     * @param $path
     *
     * @return mixed
     */
    public static function shiftByPath(&$data, $path)
    {
        $array = (array) self::getByPath($data, $path);
        $result = array_shift($array);
        self::setByPath($data, $path, $array);

        return $result;
    }

    /**
     * @param $data
     * @param $path
     *
     * @return mixed
     */
    public static function popByPath(&$data, $path)
    {
        $array = (array) self::getByPath($data, $path);
        $result = array_pop($array);
        self::setByPath($data, $path, $array);

        return $result;
    }

    /**
     * @param $data
     * @param $path
     *
     * @return int
     */
    public static function countByPath(&$data, $path)
    {
        $array = (array) self::getByPath($data, $path);
        $result = count($array);

        return $result;
    }
}