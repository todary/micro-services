<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiPlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_plans', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50);
			$table->float('plan_price', 10, 0);
			$table->integer('search_number');
			$table->float('search_price', 10, 0);
			$table->float('search_price_over', 10, 0)->comment('price after allowed search number');
			$table->integer('max_concurrent_searches')->default(0)->comment('to determine the max search number using api in the same time');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('api_plans');
	}

}
