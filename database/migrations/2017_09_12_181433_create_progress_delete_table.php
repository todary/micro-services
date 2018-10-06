<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProgressDeleteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('progress_delete', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->unsigned()->index('person_id');
			$table->string('delete_method', 20);
			$table->string('delete_category', 20)->nullable();
			$table->text('delete_request', 65535);
			$table->text('deleted_data', 16777215)->nullable();
			$table->text('deleted_results', 65535)->nullable();
			$table->text('additional_data', 65535)->nullable();
			$table->char('request_hash', 40);
			$table->timestamp('dateline')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->boolean('is_rolled_back')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('progress_delete');
	}

}
