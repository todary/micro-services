<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombinationLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combination_level', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('comb_id')->unsigned()->index('comb_id');
			$table->boolean('level_no')->default(1)->index('level_no');
			$table->string('source', 50);
			$table->text('data', 65535);
			$table->float('start_minute', 10, 0)->default(0);
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->integer('time_taken')->default(0);
			$table->boolean('started')->default(0)->index('started');
			$table->boolean('is_completed')->default(0)->index('is_completed');
			$table->boolean('trials')->default(0);
			$table->string('log_stream', 100)->nullable();
			$table->integer('exec_time')->default(0);
			$table->char('combinations_hash', 40)->nullable();
			$table->text('combs_fields', 65535)->nullable();
			$table->bigInteger('report_id');
			$table->integer('time');
			$table->boolean('enabled');
			$table->unique(['report_id','combinations_hash'], 'report_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('combination_level');
	}

}
