<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalystSearchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analyst_searches', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('person_id')->index('person_id');
			$table->integer('user_id')->index('user_id');
			$table->string('analyst_name', 100);
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->boolean('accepted')->default(0);
			$table->text('inconclusive_text', 65535);
			$table->dateTime('created')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('analyst_searches');
	}

}
