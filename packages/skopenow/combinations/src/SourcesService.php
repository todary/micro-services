<?php
namespace Skopenow\Combinations;

use Skopenow\Combinations\Interfaces\SourcesServiceInterface;

/**
* 
*/
class SourcesService implements SourcesServiceInterface
{
    protected $db;
    
    public function __construct()
    {
        $this->db = app('db');
    }

    public function getMainSourceByName($mainSourceName)
    {
        return $this->db->table('main_source')
            ->where('name', $mainSourceName)
            ->first();
    }

    public function getSourceByName($sourceName)
    {
        return $this->db->table('source')
            ->where('name', $sourceName)
            ->first();
    }

    public function getSourceById($sourceId)
    {
        return $this->db->table('source')
            ->where('id', $sourceId)
            ->first();
    }
}