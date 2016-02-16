<?php

namespace App\Grabber;

class Logger
{

    protected static $i = 0;

//    public static function page($url)
//    {
//        // $this->writeln("page :  <a href='{$url}'>$url</a>");
//        self::writeln("page :  {$url}");
//    }

    public static function log($msg)
    {
        self::writeln(' . ' . $msg);
    }

    public static function error($msg)
    {
        self::writeln(' .  .  . ' . $msg);
    }

//    /**
//     * @param $from
//     * @param $to
//     */
//    public static function upload($from , $to)
//    {
//        $i = static::$i++;
//        $size = (string) @filesize($to);
//        $size = $size . str_repeat(' ' , 10 - strlen($size));
//        // $this->writeln("uploaded {$i}:  <a href='{$from}'>{$from}</a> to <a href='{$to}'>{$to}</a>");
//        self::writeln("uploaded {$i}:  {$from}".PHP_EOL." {$size} - {$to}");
//    }

    /**
     * @param $msg
     */
    public static function writeln($msg)
    {
        echo $msg . PHP_EOL;
    }


}