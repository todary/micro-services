<?php

/**
 * Write the test cases for the cleanup entry point .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

use Skopenow\Cleanup\EntryPoint ;
use Illuminate\Support\Facades\Artisan ;
use App\Models\Result ;
use App\Models\Relationship;
use App\Models\RelationshipLinear ;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EntryPointTest extends TestCase
{
	public function setup()
	{
		$this->refreshApplication();
        $this->artisan('migrate:refresh',['--path' => 'packages/skopenow/cleanup/src/database/migrations']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback',['--path' => 'packages/skopenow/cleanup/src/database/migrations']);
        });
	}
		
	// public function testInit()
	// {
	// 	$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/cleanup/src/database/migrations']);
	// 	$relationshipLinears = factory(RelationshipLinear::class , 10)->create() ;
	// 	$relationships =  Relationship::get() ; //factory(Relationship::class , 20)->create();  
	// 	$results = Result::get()->toArray() ;
	// 	$entryPoint = new EntryPoint($results , $relationships) ;
	// 	$output = $entryPoint->process() ;

	// 	$this->assertEquals(true, true) ;
		
	// }

	public function testWithDeletedResults()
	{

		// $r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/cleanup/src/database/migrations']);
		$relationshipLinears = factory(RelationshipLinear::class , 10)->create();
		$relationships =  Relationship::get(); //factory(Relationship::class , 20)->create();  
		DB::table('result')->update(['flags' => 5]);
		$this->updateResultFlags(Result::find(1),261);
		$this->updateResultFlags(Result::find(5),517);

		$this->updateResultIsDeleted(Result::find(10),1);
		$this->updateResultIsDeleted(Result::find(15),1);
		$this->updateResultIsDeleted(Result::find(20),1);

		$results = Result::get()->toArray();

		// $cleanup = \Mockery::mock(EntryPoint::class,[$results,$relationships])->makePartial()->shouldAllowMockingProtectedMethods();
		
		$entryPoint = new EntryPoint($results , $relationships) ;
		$output = $entryPoint->process() ;

		$result1 = Result::find(1);
		$result5 = Result::find(5);
		$resultsToBeVisible = [$result1,$result5];
		$resultsToBeVisible = array_merge($resultsToBeVisible,$this->getResultsFromSources($results,[$result1['source_id'], $result5['source_id']]));
		
		dd($resultsToBeVisible,$output->getAttribute('resultsToBeVisible')); 



		dd($output);
	}

	public function getResultsFromSources(array $results, array $sources)
	{
		$sameResults = [];
		foreach ($results as $result) {
			if(in_array($result['source_id'], $sources)) {
				$sameResults[] = $result;
			}
		}
		return $sameResults;
	}

	public function updateResultFlags(Result $result ,int $flags)
	{
		$result->flags = $flags;
		$result->save();
	}

	public function updateResultIsDeleted(Result $result, $is_deleted)
	{
		$result->is_deleted = $is_deleted;
		$result->save();
	}	
	
	// public function testDataBaseRollback()
	// {
	// 	$r = Artisan::call('migrate:refresh', ['--path' => 'packages/skopenow/cleanup/src/database/migrations']);
	// 	$output = Artisan::call('migrate:rollback', ['--path' => 'packages/skopenow/cleanup/src/database/migrations']);
		
	// 	$this->assertEquals($output , 0);
	// }

}