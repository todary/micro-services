<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth', function(Blueprint $table)
		{
			$table->integer('user_id')->primary();
			$table->string('provider', 45)->nullable();
			$table->string('identifier', 64)->nullable();
			$table->text('profile_cache', 65535)->nullable();
			$table->text('session_data', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth');
	}

}
