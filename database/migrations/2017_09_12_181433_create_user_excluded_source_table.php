<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserExcludedSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_excluded_source', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('user.user_id');
			$table->integer('source_id')->unsigned()->index('source_admin.source_admin_id');
			$table->dateTime('dateline');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_excluded_source');
	}

}
