<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombinationsLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combinations_log', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->unsigned()->index('person_id');
			$table->string('source', 50)->index('source');
			$table->string('main_source', 150)->index('main_source');
			$table->text('combination', 65535);
			$table->text('second_combination', 65535);
			$table->text('third_combination', 65535);
			$table->char('combinations_hash', 40)->nullable();
			$table->text('combs_fields', 65535)->nullable();
			$table->boolean('unique_name')->nullable()->index('unique_name');
			$table->boolean('big_city')->nullable()->index('big_city');
			$table->integer('parent_res')->nullable()->index('parent_res');
			$table->bigInteger('parent_comb')->nullable()->index('parent_comb');
			$table->integer('repeat');
			$table->boolean('is_generated')->nullable()->default(0)->index('is_generated');
			$table->float('start_minute', 10, 0)->default(0);
			$table->dateTime('start_time')->default('0000-00-00 00:00:00');
			$table->dateTime('end_time')->default('0000-00-00 00:00:00');
			$table->integer('time_taken')->default(0);
			$table->integer('array')->default(0);
			$table->integer('started')->default(0)->index('started');
			$table->boolean('is_completed')->default(0)->index('is_completed');
			$table->integer('trials')->default(0);
			$table->integer('has_error')->default(0);
			$table->string('additional', 350)->nullable();
			$table->string('username', 50)->nullable();
			$table->char('version', 1)->default('S')->index('version')->comment('D=Development,B=Beta,R=Release Candidate,S=Stable');
			$table->string('log_stream', 100)->nullable();
			$table->integer('exec_time')->nullable()->default(0);
			$table->text('extra_data', 65535)->nullable()->comment('use it as Json field');
			$table->boolean('enabled')->default(1);
			$table->unique(['combinations_hash','person_id'], 'combinations_hash');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('combinations_log');
	}

}
