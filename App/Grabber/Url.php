<?php

namespace App\Grabber;

class Url
{

    const ROOT = 'root';
    const ROOT_FOLDER = 'root_folder';
    const HOST = 'host';
    const PATH = 'path';
    const PATH_FOLDER = 'path_folder';
    const PATH_FILENAME = 'path_filename';
    const QUERY = 'query';
    const FRAGMENT = 'fragment';
    const EXTENSION = 'extension';
    const TYPE = 'type';
    const TYPE_T_PAGE = 'page';
    const TYPE_T_IMAGE = 'image';
    const TYPE_T_STYLE = 'style';
    const TYPE_T_SCRIPT = 'script';
    const TYPE_T_MEDIA = 'media';
    const TYPE_T_UNDEFINED = 'undefined';


    const HASH = 'hash';
    const HASH_FILE = 'hash_ext';


    const SOURCE_PATH = 'source_path';
    const REMOTE_PATH = 'remote_path';
    const LOCAL_PATH = 'local_path';
    const REPLACE_PATH = 'replace_path';
    const IS_BELONG = 'is_belong';


    protected static $types
        = [
            self::TYPE_T_PAGE   => ['', 'php', 'html', 'htm'],
            self::TYPE_T_IMAGE  => ['jpg', 'jpeg', 'png', 'bmp', 'ico', 'gif', 'svg'],
            self::TYPE_T_MEDIA  => ['swf', 'mp4',],
            self::TYPE_T_STYLE  => ['css', 'eot', 'woff', 'woff2', 'ttf'],
            self::TYPE_T_SCRIPT => ['js'],

        ];


    public static function getInfo($url, $baseUrl)
    {

        $result = [];

        $result[self::SOURCE_PATH] = $url;
        $result[self::IS_BELONG] = self::isBelong($url, $baseUrl);
        $result[self::REMOTE_PATH] = self::remotePath($url, $baseUrl);
        $result[self::LOCAL_PATH] = self::localPath($url, $baseUrl);
        $result[self::REPLACE_PATH] = self::replacePath($url, $baseUrl);

        return $result;
    }


    public static function replacePath($url, $baseUrl)
    {
        $remote_path = self::remotePath($url, $baseUrl);
        $local_path = self::localPath($url, $baseUrl);
        $is_belong = self::isBelong($url, $baseUrl);

        if (!$is_belong) {
            return null;
        }

        $postfix = '';

        $parts = explode('?', $remote_path);


        $postfix = $postfix . ((@$parts[1]) ? '?' . $parts[1] : '');
        if (!self::isContain($postfix, '#')) {
            $parts = explode('#', $remote_path);
            $postfix = $postfix . ((@$parts[1]) ? '#' . $parts[1] : '');
        }

        if ($postfix === '') {

            if (self::isContain($remote_path, '?') && self::isContain($remote_path, '#')) {
                $postfix = '?#';
            } elseif (self::isContain($remote_path, '?')) {
                $postfix = '?';
            } elseif (self::isContain($remote_path, '#')) {
                $postfix = '#';
            }

        }

        if ($postfix === '' && self::isContain($remote_path, '#')) {
            $postfix .= '#';
        }

        if (isset($url[0]) && $url[0] === '#') {
            $replace_path = $postfix;
        } else {
            $replace_path = $local_path . $postfix;
        }


        return $replace_path;
    }


    public static function isBelong($url, $baseUrl)
    {

        $root = Url::rel2abs('/', $baseUrl);
        $remote_path = self::rel2abs($url, $baseUrl);

        return (str_replace($root, '@', $remote_path) !== $remote_path);
    }

    public static function remotePath($url, $baseUrl)
    {
        return self::rel2abs($url, $baseUrl);
    }

    public static function localPath($url, $baseUrl)
    {

        $root = Url::rel2abs('/', $baseUrl);
        $remote_path = self::remotePath($url, $baseUrl);
        $is_belong = self::isBelong($url, $baseUrl);

        if (!$is_belong) {
            return null;
        }

        $type = self::info($remote_path, self::TYPE);


        $local_path = str_replace($root, '', self::rel2abs($remote_path, $baseUrl));

        $parts = explode('#', $local_path);
        $local_path = $parts[0];
        $parts = explode('?', $local_path);
        $local_path = $parts[0];


        // remove # and ?
        // if page then long name
        if ($type === self::TYPE_T_PAGE) {

            $local_path = rtrim($local_path, '/');
            $local_path = strtr($local_path, [
                '.php'  => '',
                '.html' => '',
                '.htm'  => '',
                '/'     => '_',
                '.'     => '_',
            ]);

            if ($local_path === '') {
                $local_path = 'index';
            }

            if ($local_path === '/') {
                $local_path = 'index';
            }

            $local_path = $local_path . '.html';
        } else {

        }

        return $local_path;
    }


    public static function remoteInfo($url, $targetUrl, $flag = null)
    {

        $remote_path = self::rel2abs($url, $targetUrl);

        $info = array_merge(self::info($remote_path), self::getInfo($url, $targetUrl));

        return (null === $flag)
            ? $info
            : $info[$flag];
    }

    protected static function isContain($where, $what)
    {
        return strpos($where, $what) !== false;
    }

    public static function info($url, $flag = null)
    {

        $info = [
            self::ROOT          => null,    // +
            self::ROOT_FOLDER   => null,    // +
            self::HOST          => null,    // +
            self::PATH          => null,    // +
            self::PATH_FOLDER   => null,    // +
            self::PATH_FILENAME => null,    // +
            self::QUERY         => null,    // +
            self::FRAGMENT      => null,    // +
            self::EXTENSION     => null,    // +
            self::TYPE          => null,    // +
            self::HASH          => null,    // +
            self::HASH_FILE     => null,    // +
        ];


        $parse = parse_url($url);

        // QUERY ==================================

        $info[self::QUERY] = array_key_exists('query', $parse)
            ? $parse['query']
            : null;

        // FRAGMENT ==================================

        $info[self::FRAGMENT] = array_key_exists('fragment', $parse)
            ? $parse['fragment']
            : null;

        // HOST ==================================

        $info[self::HOST] = array_key_exists('host', $parse)
            ? $parse['host']
            : null;

        // ROOT ==================================

        $info[self::ROOT] = array_key_exists('scheme', $parse)
            ? $parse['scheme'] . '://' . $info[self::HOST] . '/'
            : null;

        // PATH ==================================

        $info[self::PATH] = array_key_exists('path', $parse)
            ? $parse['path']
            : null;

        $pathInfo = pathinfo($info[self::PATH]);

        // PATH_FOLDER / PATH_FILENAME ==================================

        if (substr($info[self::PATH], -1) === '/') {
            $info[self::PATH_FOLDER] = substr($info[self::PATH], 0, -1);
            $info[self::PATH_FILENAME] = null;
        } else {

            $info[self::PATH_FOLDER] = array_key_exists('dirname', $pathInfo)
                ? $pathInfo['dirname']
                : null;

            $info[self::PATH_FILENAME] = array_key_exists('basename', $pathInfo)
                ? $pathInfo['basename']
                : null;
        }

        if (substr($info[self::PATH_FOLDER], 0, 1) === '/') {
            $info[self::PATH_FOLDER] = substr($info[self::PATH_FOLDER], 1);
        }


        // EXTENSION ==================================

        $info[self::EXTENSION] = array_key_exists('extension', $pathInfo)
            ? strtolower($pathInfo['extension'])
            : null;


        // TYPE ==================================
        $info[self::TYPE] = self::TYPE_T_UNDEFINED;
        foreach (self::$types as $type => $array) {
            if (in_array($info[self::EXTENSION], $array)) {
                $info[self::TYPE] = $type;
                break;
            }
        }

        // HASH ==================================

        $info[self::HASH] = md5(implode('%%', array_values($info)));

        // HASH_FILE ==================================

        $info[self::HASH_FILE]
            = $info[self::HASH] . '.' . $info[self::EXTENSION];

        // ROOT_FOLDER ==================================

        if ($info[self::HOST]) {
            $info[self::ROOT_FOLDER]
                = $info[Url::ROOT] . $info[Url::PATH_FOLDER] . '/';
        }

//        $info['debug_pathinfo'] = $pathInfo;
//        $info['debug_parse'] = $parse;

        return (null === $flag)
            ? $info
            : $info[$flag];
    }


    public static function rel2abs($url, $base)
    {

        $result = '';

        if (strpos($url, '://')) { //already absolute
            $result = $url;
        } elseif (substr($url, 0, 2) == '//') { //shorthand scheme
            $result = 'http:' . $url;
        } elseif (@$url[0] === '/') { //just add domain
            $result = parse_url($base, PHP_URL_SCHEME) . '://' . parse_url($base, PHP_URL_HOST) . $url;
        } elseif (strpos($base, '/', 9) === false) { //add slash to domain if needed
            $result = $base .= '/';
        } else { //for relative links, gets current directory and appends new filename
            $result = substr($base, 0, strrpos($base, '/') + 1) . $url;
        }


        $parts = explode('/', $result);
        $sanitize = [];
        foreach ($parts as $seg) {
//            $i = 10;
//            while($i--){
//                if(str_repeat('.' , $i ) === $seg){
//
//                }
//            }

            switch ($seg) {
                case '.':
                    break;
                case '..':
                    array_pop($sanitize);
                    break;
                case '...':
                    array_pop($sanitize);
                    array_pop($sanitize);
                    break;
                case '....':
                    array_pop($sanitize);
                    array_pop($sanitize);
                    array_pop($sanitize);
                    break;
                case '.....':
                    array_pop($sanitize);
                    array_pop($sanitize);
                    array_pop($sanitize);
                    array_pop($sanitize);
                    break;
                default:
                    $sanitize[] = $seg;
            }
        }

        $result = implode('/', $sanitize);

        return $result;
    }


    public static function abs2local($abs, $base)
    {

        $defaults = 'index';

        if ($abs === '/') {
            $abs .= $defaults;
        }

        $abs = str_replace('/?', '/' . $defaults . '?', $abs);
        $abs = str_replace('/#', '/' . $defaults . '#', $abs);

        $filePath = str_replace(self::rel2abs('/', $base), '', self::rel2abs($abs, $base));

        // if EXTENSION not exists then write HTML extension

        return $filePath;
    }


}