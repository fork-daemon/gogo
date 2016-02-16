<?php

namespace App\Grabber;

use App\Grabber\Exception\GrabberException;

class Resource
{


    public $targetUrl;
    public $targetLocal;
    public $source;

    public $resource = [];
    public $replaceSource;


    protected $resourcePatterns
        = [
            '/href=\'([^\']*)\'/i',
            '/href="([^"]*)"/i',
            '/src=\'([^\']*)\'/i',
            '/src="([^"]*)"/i',
            '/src:\'([^\']*)\'/i',          // js src:'images/cloud.png'
            '/src:"([^"]*)"/i',             // js  src:"images/cloud.png"

            '/import \'([^\']*)\'/i',       // css @import 'reset.css';
            '/import "([^"]*)"/i',          // css @import "reset.css";

            // '/url\(\'([^\'\)]*)\'\)/i',  // css url('xxx.xxx');
            // '/url\("([^"\)]*)"\)/i',     // css url("xxx.xxx");
            '/url\(([^\)]*)\)/i',           // css url(xxx.xxx);

            '/action=\'([^\']*)\'/i',       // form  action='/search.html'
            '/action="([^"]*)"/i',          // form action="/search.html"

            '/data-videomp4=\'([^\']*)\'/i',// data-videomp4='video/video-2.mp4'
            '/data-videomp4="([^"]*)"/i',   // data-videomp4="video/video-2.mp4"

            '/data-url=\'([^\']*)\'/i',// data-url='video/video-2.mp4'
            '/data-url="([^"]*)"/i',   // data-url="video/video-2.mp4"


        ];

    protected $replacePatterns
        = [
            '=\':find:\'' => '=\':local:\'',
            '=":find:"'   => '=":local:"',
            '(:find:)'    => '(:local:)',
            ':\':find:\'' => ':\':local:\'',
            ':":find:"'   => ':":local:"',
        ];

    // region OPTIONS *********************************************************

    /**
     * @return mixed
     * @throws GrabberException
     */
    public function getTargetUrl()
    {
        if (null === $this->targetUrl) {
            throw new GrabberException("Resource: targetUrl undefined");
        }

        return $this->targetUrl;
    }

    /**
     * @param $url
     *
     * @return $this
     * @throws GrabberException
     */
    protected function setTargetUrl($url)
    {
        if (empty($url)) {
            throw new GrabberException("Resource: url must be set");
        }

        $this->targetUrl = $url;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param $source
     *
     * @return $this
     */
    protected function setSource($source)
    {
        $this->source = $source;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getReplaceSource()
    {
        return $this->replaceSource;
    }

    /**
     * @param $replaceSource
     *
     * @return $this
     */
    protected function setReplaceSource($replaceSource)
    {
        $this->replaceSource = $replaceSource;

        return $this;
    }


    /**
     * @return array
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param $resource
     *
     * @return $this
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetLocal()
    {
        return $this->targetLocal;
    }

    /**
     * @param $targetLocal
     *
     * @return $this
     */
    protected function setTargetLocal($targetLocal)
    {
        $this->targetLocal = $targetLocal;

        return $this;
    }


    // endregion *********************************************************

    /**
     * @param $source
     * @param $targetUrl
     *
     * @throws GrabberException
     */
    public function __construct($source, $targetUrl)
    {
        $this->setTargetUrl($targetUrl);
        $this->setSource($source);

        $this->findResource();
        $this->replaceSource();

    }


    /**
     * @return $this
     * @throws GrabberException
     */
    protected function findResource()
    {

        $source = $this->getSource();
        $targetUrl = $this->getTargetUrl();
        $patterns = $this->resourcePatterns;

        // find all =============================================
        $finds = [];
        foreach ($patterns as $regex) {
            $matches = null;
            preg_match_all($regex, $source, $matches);
            $finds = array_merge($finds, $matches[1]);
        }

        // sanitize =============================================
        $sanitize = [];
        foreach ($finds as $item) {

            // image.png => image.png , 'image.png' => image.png , "image.png" => image.png
            $item = trim($item, '\'');
            $item = trim($item, '"');

            // href='mailto:mail@site.com' , href='javascript:;'
            if (str_replace('mailto:', '', $item) !== $item || str_replace('javascript:', '', $item) !== $item) {
                continue;
            }

            $sanitize[] = $item;
        }
        $sanitize = array_unique($sanitize);

        // build =============================================
        $resources = [];
        foreach ($sanitize as $item) {
            $resources[] = Url::remoteInfo($item, $targetUrl);
        }

        $this->setResource($resources);

        return $this;
    }

    /**
     * @return $this
     */
    protected function replaceSource()
    {
        $replace = [];

        foreach ($this->resource as $i => $item) {
            if ($item[Url::IS_BELONG]) {

                $find_path = $item[Url::SOURCE_PATH];
                $replace_path = $item[Url::REPLACE_PATH];

                foreach ($this->replacePatterns as $replaceFind => $replaceLocal) {
                    $replaceFind = str_replace(':find:', $find_path, $replaceFind);
                    $replaceLocal = str_replace(':local:', $replace_path, $replaceLocal);
                    $replace[$replaceFind] = $replaceLocal;
                }
            }
        }

        $source = $this->getSource();
        $source = strtr($source, $replace);
        $this->setReplaceSource($source);

        return $this;
    }

}