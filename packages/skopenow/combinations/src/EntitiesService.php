<?php
namespace Skopenow\Combinations;

use Skopenow\Combinations\Interfaces\EntitiesServiceInterface;

/**
* 
*/
class EntitiesService implements EntitiesServiceInterface
{
    protected $db;
    
    public function __construct()
    {
        $this->db = app('db');
    }

    public function createCombinationEntity($reportId)
    {
        $entityId = $this->db->table('entity')
            ->insertGetId([
                'type' => 'combination',
                'report_id' => $reportId,
            ]);
        return $entityId;
    }
}