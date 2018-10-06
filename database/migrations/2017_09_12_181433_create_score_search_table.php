<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScoreSearchTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('score_search', function(Blueprint $table)
		{
			$table->boolean('id')->primary();
			$table->string('key', 20)->unique('key');
			$table->string('title', 30);
			$table->float('score', 10, 0)->default(0)->index('score');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('score_search');
	}

}
