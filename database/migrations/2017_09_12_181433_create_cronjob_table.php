<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCronjobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cronjob', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('minute', 20);
			$table->string('hour', 20);
			$table->string('day', 20);
			$table->string('month', 20);
			$table->string('week', 20)->default('*')->comment('0 (for Sunday) through 6 (for Saturday)');
			$table->string('target', 250);
			$table->char('type', 1)->comment('c=>command, m=>method, e=>eval');
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cronjob');
	}

}
