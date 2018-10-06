<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmitsErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emits_errors', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('person_id')->index('person');
			$table->text('message', 65535);
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
		Schema::drop('emits_errors');
	}

}
