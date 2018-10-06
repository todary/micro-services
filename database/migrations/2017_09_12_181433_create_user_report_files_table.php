<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserReportFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_report_files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('report_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('title', 200);
			$table->string('search_tag', 50)->nullable();
			$table->string('url', 200);
			$table->integer('person_id')->default(0);
			$table->bigInteger('result_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_report_files');
	}

}
