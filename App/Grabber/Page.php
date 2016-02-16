<?php

namespace App\Grabber;

use App\Grabber\Exception\GrabberException;

class Page
{
    protected $targetUrl; // http://7rulonov.com/
    protected $outputFolder; // _output/7rulonov.com/

    protected $file;
    protected $resource;

    public $uploaded = [];

    // region OPTIONS *********************************************************

    /**
     * @return mixed
     * @throws GrabberException
     */
    protected function getTargetUrl()
    {

        if (null === $this->targetUrl) {
            throw new GrabberException("Page: targetUrl undefined");
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
            throw new GrabberException("Page: url must be set");
        }

        $this->targetUrl = $url;

        return $this;
    }

    /**
     * @return mixed
     * @throws GrabberException
     */
    protected function getOutputFolder()
    {

        if (null === $this->outputFolder) {
            throw new GrabberException("Page: outputFolder undefined");
        }

        return $this->outputFolder;
    }

    /**
     * @param $folder
     *
     * @return $this
     * @throws GrabberException
     */
    protected function setOutputFolder($folder)
    {

        if (empty($folder) || $folder === '/') {
            throw new GrabberException("Page: folder can not be 'empty' or '/'");
        }

        $this->outputFolder = rtrim($folder, '/') . '/';

        return $this;
    }

    // endregion *********************************************************

    /**
     * @param $targetUrl
     * @param $outputFolder
     *
     * @throws GrabberException
     */
    public function __construct($targetUrl, $outputFolder)
    {
        $this->setTargetUrl($targetUrl);
        $this->setOutputFolder($outputFolder);
    }

    /**
     * @throws GrabberException
     */
    public function save()
    {

        $targetUrl = $this->getTargetUrl();

        $this->file = new File($this->getOutputFolder());

        $source = $this->file->getCache($targetUrl);

        $resource = new Resource($source, $targetUrl);

        $targetLocal = Url::remoteInfo($targetUrl, $targetUrl, Url::LOCAL_PATH);

        $replaceSource = $resource->getReplaceSource();
        $this->file->put($targetLocal, $replaceSource);

        $items = $resource->getResource();

        $saveTypes = [Url::TYPE_T_SCRIPT, Url::TYPE_T_STYLE, Url::TYPE_T_IMAGE, Url::TYPE_T_MEDIA];


        $scope = [];
        foreach ($items as $i => $item) {

            if(isset($scope[$item[Url::LOCAL_PATH]])){
                continue;
            }

            $scope[$item[Url::LOCAL_PATH]] = true;


            if (!$item[Url::IS_BELONG]) {
                continue;
            }

            if (!in_array($item[Url::TYPE], $saveTypes)) {
                continue;
            }

            if ($this->file->isUploadedLocal($item[Url::LOCAL_PATH])) {
                continue;
            }

            $this->file->save($item[Url::REMOTE_PATH], $item[Url::LOCAL_PATH]);


            Logger::log("upload {$item[Url::REMOTE_PATH]}");
            Logger::log("     > {$this->file->getLocalPath($item[Url::LOCAL_PATH])}}");

            if ($item[Url::TYPE] === Url::TYPE_T_STYLE) {
                Logger::log("start new page {$item[Url::REMOTE_PATH]}");
                $css = new Page($item[Url::REMOTE_PATH], $this->getOutputFolder());
                $css->save();
            }

        }
    }

}