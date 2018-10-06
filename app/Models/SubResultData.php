<?php

namespace App\Models;

class SubResultData implements SubResultDataInterface
{

	public $id = null;
	public $report_id = null;
    public $type = "spidered";
    public $url = "";
    public $unique_url = "";
    public $mainSource = "";
    public $source = "";
    public $screenshotUrl = null;
    public $entity_id = null;
    public $parent_id = null;
    public $rank = 0;
    public $isDelete = 0;
    public $invisible = 0;
    public $is_parent = 0;

    public function __construct(string $url)
    {
    	$this->url = $url;
    	$this->screenshotUrl = $url;
    	$this->setUniqueUrl();
        $this->setSourceFromUrl();
    }

    public function setUniqueUrl(string $url = "")
    {
        if (!$url) {
            $url = $this->url;
        }
        $urlInfo = loadService('urlInfo');
        $this->unique_url = $urlInfo->prepareContent($url);
    }

    public function setSourceFromUrl($url = "")
    {
        if (!$url) {
            $url = $this->url;
        }
        $urlInfo = loadService('urlInfo');
        $sourceInfo = $urlInfo->determineSourceAssoc($url);
        $this->mainSource = $sourceInfo['mainSource'];
        $this->source = $sourceInfo['source'];
    }


}