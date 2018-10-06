<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserOauthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_oauth', function(Blueprint $table)
		{
			$table->integer('user_id')->index('oauth_user_id');
			$table->string('provider', 45);
			$table->string('identifier', 64);
			$table->text('profile_cache', 65535)->nullable();
			$table->text('session_data', 65535)->nullable();
			$table->primary(['provider','identifier']);
			$table->unique(['user_id','provider'], 'unic_user_id_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_oauth');
	}

}
