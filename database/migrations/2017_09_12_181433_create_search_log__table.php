<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('search_log_', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('person_id')->index('person_id');
			$table->bigInteger('combination_id')->index('combination_id');
			$table->string('source', 300)->index('source');
			$table->string('desc', 300);
			$table->string('link', 350)->index('link');
			$table->text('content', 16777215)->nullable();
			$table->text('results')->nullable();
			$table->text('additional_data', 65535)->nullable();
			$table->float('time_taken', 10, 0)->nullable();
			$table->timestamp('timestamp')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('search_log_');
	}

}
