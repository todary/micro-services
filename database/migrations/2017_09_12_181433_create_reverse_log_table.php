<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReverseLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reverse_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('reverse_type', 200);
			$table->timestamp('time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('user_id');
			$table->bigInteger('person_id');
			$table->integer('result_count');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reverse_log');
	}

}
