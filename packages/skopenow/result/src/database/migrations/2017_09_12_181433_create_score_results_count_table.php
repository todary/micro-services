<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoreResultsCountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('score_results_count', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('from_num')->nullable();
			$table->integer('to_num');
			$table->float('score', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('score_results_count');
	}

}
