<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombinationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combination', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary();
			$table->bigInteger('report_id')->unsigned()->index('fk_combination_1_idx');
			$table->integer('source_id')->index('fk_combination_2_idx');
			$table->boolean('unique_name')->nullable()->index('unique_name');
			$table->boolean('big_city')->nullable()->index('big_city');
			$table->boolean('is_generated')->nullable()->default(0)->index('is_generated');
			$table->string('additional', 350)->nullable();
			$table->string('username', 50)->nullable();
			$table->char('version', 1)->default('S')->index('version')->comment('D=Development,B=Beta,R=Release Candidate,S=Stable');
			$table->text('extra_data', 65535)->nullable()->comment('use it as Json field');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('combination');
	}

}
