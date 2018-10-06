<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePendingResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pending_results', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->unsigned();
			$table->bigInteger('combination_id')->unsigned();
			$table->text('data', 65535);
			$table->boolean('is_profile_image')->default(0);
			$table->string('unique_content', 350);
			$table->index(['person_id','unique_content'], 'id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pending_results');
	}

}
