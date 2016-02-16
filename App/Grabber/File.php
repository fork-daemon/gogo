<?php

namespace App\Grabber;

use App\Grabber\Exception\GrabberException;

class File
{
    /**
     * @var string
     */
    protected $folder;

    // region OPTIONS *********************************************************

    /**
     * @param $folder
     *
     * @throws GrabberException
     */
    public function setFolder($folder)
    {
        if (empty($folder) || $folder === '/') {
            throw new GrabberException("File: setFolder");
        }

        $this->folder = rtrim($folder, '/') . '/';
    }

    /**
     * @return mixed
     * @throws GrabberException
     */
    public function getFolder()
    {
        if (null === $this->folder) {
            throw new GrabberException("File: getFolder");
        }

        return $this->folder;
    }

    /**
     * @return string
     * @throws GrabberException
     */
    public function getCacheFolder()
    {
        return $this->getFolder() . '_cache/';
    }

    // endregion *********************************************************

    /**
     * @param $folder
     *
     * @throws GrabberException
     */
    public function __construct($folder)
    {
        $this->setFolder($folder);
    }









    public function put($local, $source, $rewrite = false)
    {
        $local = $this->getLocalPath($local);

        return self::putSource($local, $source, $rewrite);
    }


    public function getLocalPath($local)
    {
        $local = $this->getFolder() . $local;

        return $local;
    }



    public function isUploadedLocal($local)
    {
        $local = $this->getLocalPath($local);

        return self::exists($local);
    }



    public function save($url, $local)
    {
        $local = $this->getLocalPath($local);

        if (!self::exists($local)) {
            $source = $this->getCache($url);
            File::putSource($local, $source, false);
        }

        return $this;
    }



    // region CACHE *********************************************************


    /**
     * @param $url
     *
     * @return string
     */
    public function putCache($url)
    {
        $path = $this->getCachePath($url);

        if (!File::exists($path)) {
            File::putSource($path, File::getSource($url));
        }

        return $path;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getCache($url)
    {
        $path = $this->putCache($url);
        return self::getSource($path);
    }

    /**
     * @param $url
     *
     * @return string
     */
    protected function getCachePath($url)
    {
        return $this->getCacheFolder() . Url::info($url, Url::HASH_FILE);
    }


    // endregion *********************************************************

    // region STATIC HELPERS *********************************************************

    /**
     * @param $path
     *
     * @return bool
     */
    public static function exists($path)
    {
        return is_file($path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public static function getSource($path)
    {
        return @file_get_contents($path);
    }

    /**
     * @param      $path
     * @param      $source
     * @param bool $rewrite
     *
     * @return bool|int
     */
    public static function putSource($path, $source, $rewrite = false)
    {
        @mkdir(Url::info($path, Url::PATH_FOLDER), 0777, true);

        if ($rewrite || !self::exists($path)) {
            return file_put_contents($path, $source);
        }

        return false;
    }

    // endregion *********************************************************

}