<?php

use Skopenow\Result\DataSourceBridge;
use Skopenow\Result\VisibleResult;

class VisibleResultTest extends TestCase
{
	protected $dataSourceMoc;
	protected $visibleResult;

	public function setup()
	{
		$this->dataSourceMoc = 
			$this->getMockBuilder(DataSourceBridge::class)->getMock();

		$this->dataSourceMoc->method("updateResults")
		->willReturn(true);

		$this->visibleResult = new VisibleResult($this->dataSourceMoc);
	}

	public function testVisibleResultsEmptyIds()
	{
		$resultsIds = [];

		$this->assertFalse($this->visibleResult->visibleResults($resultsIds,1));
	}

	public function testVisibleResults()
	{
		$resultsIds = [1,2,3,4,5];

		$this->assertTrue($this->visibleResult->visibleResults($resultsIds,1));
	}
}