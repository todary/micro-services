<?php
namespace Skopenow\Combinations\Interfaces;

interface SourcesServiceInterface
{
    public function getSourceByName($sourceName);

    public function getSourceById($sourceId);
}
