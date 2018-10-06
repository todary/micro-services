<?php

use Illuminate\Support\Facades\Artisan ;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Skopenow\Result\Banned;

class BannedTest extends TestCase
{
	use DatabaseMigrations;

	protected $banned;

	public function setup()
	{
		$this->banned = new Banned;
	}

	public function testGetUserBanned()
	{
		
	}
}