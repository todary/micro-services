<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApisLimitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('apis_limits', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('api', 150)->index('api');
			$table->integer('count')->default(0);
			$table->string('day', 10)->nullable();
			$table->string('month', 10)->index('month');
			$table->string('year', 10)->index('year');
			$table->integer('sent')->default(0)->index('sent');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('apis_limits');
	}

}
