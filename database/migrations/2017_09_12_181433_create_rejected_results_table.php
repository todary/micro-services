<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRejectedResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rejected_results', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('person_id');
			$table->string('link', 350);
			$table->string('check_status', 4000)->nullable();
			$table->integer('combination_id')->nullable();
			$table->unique(['person_id','link'], 'per_res');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rejected_results');
	}

}
