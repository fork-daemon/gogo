<?php

namespace App\Grabber;

use App\Service;

class Site
{


    /**
     * @param     $targetUrl
     * @param     $outputFolder
     * @param int $deep
     */
    public function __construct($targetUrl, $outputFolder, $deep = 1)
    {

        if ($deep === 0) {
            return;
        }
        $deep--;

        // save it self
        $page = new Page($targetUrl, $outputFolder);
        $page->save();

        // save children pages
        $this->file = new File($outputFolder);
        $source = $this->file->getCache($targetUrl);

        $resource = new Resource($source, $targetUrl);
        $items = $resource->getResource();

        foreach ($items as $i => $item) {
            if ($item[Url::IS_BELONG] && in_array($item[Url::TYPE], [Url::TYPE_T_PAGE])) {
                Service::logger()->addNotice("new site {$item[Url::REMOTE_PATH]}", ['grabber', __CLASS__]);
                $site = new Site($item[Url::REMOTE_PATH], $outputFolder, $deep);
            }
        }
    }
}