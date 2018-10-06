<?php

use Skopenow\Combinations\CombinationProcessManager;
use Skopenow\Combinations\SourcesService;
use Skopenow\Combinations\EntitiesService;
use Skopenow\Combinations\RelationshipsService;

use Skopenow\Combinations\Models\Combination;
use Skopenow\Combinations\Models\CombinationLevel;

use Illuminate\Support\Facades\Artisan;

/**
* Test cases for combination process manager
*/
class CombinationProcessManagerTest extends TestCase
{
    public function setup()
    {
        $r = Artisan::call('migrate:refresh', ['--path' => 'app/../packages/skopenow/combinations/src/database/migrations']);
        // dd(Artisan::output());
        $sourcesService = $this->createMock(SourcesService::class);
        $source = new stdClass();
        $source->id = 1;
        $source->name = 'google';
        $sourcesService->method('getSourceByName')->willReturn($source);
        $sourcesService->method('getSourceById')->willReturn($source);

        $entitiesService = $this->createMock(EntitiesService::class);
        $entitiesService->method('createCombinationEntity')->willReturn(10);
        


        $this->combinationProcessManager = new CombinationProcessManager(
            $sourcesService,
            $entitiesService
        );
    }
    

    public function testStore()
    {
        $levels = [
            ['source' => 'google', 'level_number' => 1, 'data' => 'any data'],
            ['source' => 'google', 'level_number' => 2, 'data' => ['any data']],
        ];
        $combinationId = $this->combinationProcessManager->store(1, 'google', $levels);
        $this->assertEquals(10, $combinationId);

        $count = app('db')->table('combination')->count();
        $this->assertEquals(1, $count);
        
        $count = app('db')->table('combination_level')->count();
        $this->assertEquals(2, $count);

        $count = app('db')->table('combination_level')->where('enabled', 1)->count();
        $this->assertEquals(1, $count);

        config(['state.version'=> 'S']);

        $combination = app('db')->table('combination')->where('id', $combinationId)->first();
        $this->assertEquals(1, $combination->source_id);
        $this->assertNull($combination->unique_name);
        $this->assertNull($combination->big_city);
        $this->assertEquals(0, $combination->is_generated);
        $this->assertNull($combination->additional);
        $this->assertNull($combination->username);
        // $this->assertEquals("D", $combination->version);
        $this->assertNull($combination->extra_data);
    }

    // public function testStoreWithData()
    // {
    //     $data = [
    //         'unique_name' => 1,
    //         'big_city' => 1,
    //         'is_generated' => 1,
    //         'additional' => "additional",
    //         'username' => "username",
    //         'extra_data' => "extra_data"
    //     ];

    //     $combinationId = $this->combinationProcessManager->store(1, 'google', $data);
    //     $this->assertEquals(10, $combinationId);

    //     $combination = app('db')->table('combination')->where('id', $combinationId)->first();

    //     $this->assertEquals(1, $combination->source_id);
    //     $this->assertEquals(1, $combination->unique_name);
    //     $this->assertEquals(1, $combination->big_city);
    //     $this->assertEquals(1, $combination->is_generated);
    //     $this->assertEquals("additional", $combination->additional);
    //     $this->assertEquals("username", $combination->username);
    //     $this->assertEquals("extra_data", $combination->extra_data);
    // }

    // public function testStoreWithParentCombination()
    // {
    //     $reportId = 2;
    //     $entityId = 1;
    //     $parentCombinationId = 5;

    //     $sourcesService = $this->createMock(SourcesService::class);
    //     $source = new stdClass();
    //     $source->id = 1;
    //     $source->name = 'google';
    //     $sourcesService->method('getSourceByName')->willReturn($source);
    //     $sourcesService->method('getSourceById')->willReturn($source);

    //     $entitiesService = $this->createMock(EntitiesService::class);
    //     $entitiesService->method('createCombinationEntity')->willReturn($entityId);
        
    //     $relationshipsService = $this->createMock(RelationshipsService::class);
    //     $relationshipsService
    //         ->expects($this->once())
    //         ->method('setCombinationParentCombination')
    //         ->with(
    //             $this->equalTo($reportId),
    //             $this->equalTo($entityId),
    //             $this->equalTo($parentCombinationId)
    //         );

    //     $combinationProcessManager = new CombinationProcessManager(
    //         $sourcesService,
    //         $entitiesService,
    //         $relationshipsService
    //     );

    //     $combinationId = $combinationProcessManager->store($reportId, 'google', [], $parentCombinationId);
    // }

    // public function testStoreWithParentResult()
    // {
    //     $reportId = 2;
    //     $entityId = 1;
    //     $parentResultId = 7;

    //     $sourcesService = $this->createMock(SourcesService::class);
    //     $source = new stdClass();
    //     $source->id = 1;
    //     $source->name = 'google';
    //     $sourcesService->method('getSourceByName')->willReturn($source);
    //     $sourcesService->method('getSourceById')->willReturn($source);

    //     $entitiesService = $this->createMock(EntitiesService::class);
    //     $entitiesService->method('createCombinationEntity')->willReturn($entityId);
        
    //     $relationshipsService = $this->createMock(RelationshipsService::class);

    //     $relationshipsService
    //         ->expects($this->once())
    //         ->method('setCombinationParentResult')
    //         ->with(
    //             $this->equalTo($reportId),
    //             $this->equalTo($entityId),
    //             $this->equalTo($parentResultId)
    //         );

    //     $combinationProcessManager = new CombinationProcessManager(
    //         $sourcesService,
    //         $entitiesService,
    //         $relationshipsService
    //     );

    //     $combinationId = $combinationProcessManager->store($reportId, 'google', [], null, $parentResultId);
    // }

    public function testAddCombinationLevel()
    {
        $combinationId = $this->combinationProcessManager->store(1, 'google', []);

        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_1');
        $combinationLevel = app('db')->table('combination_level')->where('id', $combinationLevelId)->first();
        $this->assertNotNull($combinationLevel);
        if ($combinationLevel) {
            $this->assertEquals(json_encode('level_1'), $combinationLevel->data);
            $this->assertEquals('google', $combinationLevel->source);
            $this->assertEquals(1, $combinationLevel->level_no);
            $this->assertEquals(1, $combinationLevel->enabled);
        }

        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_3');
        $combinationLevel = app('db')->table('combination_level')->where('id', $combinationLevelId)->first();
        $this->assertNotNull($combinationLevel);
        if ($combinationLevel) {
            $this->assertEquals(json_encode('level_3'), $combinationLevel->data);
            $this->assertEquals('google', $combinationLevel->source);
            $this->assertEquals(2, $combinationLevel->level_no);
            $this->assertEquals(0, $combinationLevel->enabled);
        }
    }

    public function testGetPendingCombs()
    {
        $combinationId = $this->combinationProcessManager->store(1, 'google');
        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_1');
        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_2');
        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_3');
        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_4');

        $pendingCombs = $this->combinationProcessManager->getPendingCombs(1);
        $this->assertEquals(1, count($pendingCombs));
        $this->assertEquals(json_encode('level_1'), $pendingCombs[0]->data);
    }

    public function testOnLevelStart()
    {
        $combinationId = $this->combinationProcessManager->store(1, 'google');
        $combinationLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_1');
        $this->combinationProcessManager->onLevelStart($combinationLevelId);
        $combinationLevel = app('db')->table('combination_level')->where('id', $combinationLevelId)->first();
        $this->assertNotNull($combinationLevel->start_time);
        $this->assertEquals(1, $combinationLevel->started);
    }

    public function testOnLevelEnd()
    {
        $combinationId = $this->combinationProcessManager->store(1, 'google');
        $firstCombLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_1');
        $secondCombLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_2');
        $thirdCombLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_3');
        $fourthCombLevelId = $this->combinationProcessManager->addCombinationLevel(1, $combinationId, 'google', 'level_4');
        
        //run first level
        $this->combinationProcessManager->onLevelStart($firstCombLevelId);
        $this->combinationProcessManager->onLevelEnd($firstCombLevelId, false);
        
        $combLevel = $this->getCombinationLevel($firstCombLevelId);
        $this->assertNotNull(1, $combLevel->end_time);
        
        $combLevel = $this->getCombinationLevel($secondCombLevelId);
        $this->assertEquals(1, $combLevel->enabled);
        
        //run second level
        $this->combinationProcessManager->onLevelStart($secondCombLevelId);
        $this->combinationProcessManager->onLevelEnd($secondCombLevelId, true);

        $combLevel = $this->getCombinationLevel($secondCombLevelId);
        $this->assertNotNull(1, $combLevel->end_time);

        $combLevel = $this->getCombinationLevel($thirdCombLevelId);
        $this->assertEquals(0, $combLevel->enabled);
    }

    protected function getCombinationLevel($combinationLevelId)
    {
        return app('db')->table('combination_level')->where('id', $combinationLevelId)->first();
    }

    protected function getCombination($combinationLevelId)
    {
        return app('db')->table('combination')->where('id', $combinationLevelId)->first();
    }
}
