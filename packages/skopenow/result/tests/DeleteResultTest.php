<?php

use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\DeleteResult;

class DeleteResultTest extends TestCase
{
	protected $dataSourceMoc;
	protected $deleteResult;

	public function setup()
	{
		$this->dataSourceMoc = 
			$this->getMockBuilder(DataSourceBridge::class)->getMock();

		$this->dataSourceMoc->method("updateResults")
		->willReturn(true);

		$this->deleteResult = new DeleteResult($this->dataSourceMoc);
	}

	public function testUpdateDisplayLevelEmptyIds()
	{
		$resultsIds = [];

		$this->assertFalse($this->deleteResult->updateDisplayLevel($resultsIds,1));
	}

	public function testUpdateDisplayLevel()
	{
		$resultsIds = [1,2,3,4,5];

		$this->assertTrue($this->deleteResult->updateDisplayLevel($resultsIds,1));
	}

	public function testDeleteResultEmptyIds()
	{
		$resultsIds = [];

		$this->assertFalse($this->deleteResult->deleteResult($resultsIds,1));
	}

	public function testDeleteResult()
	{
		$resultsIds = [1,2,3,4,5];

		$this->assertTrue($this->deleteResult->deleteResult($resultsIds,1));
	}
}