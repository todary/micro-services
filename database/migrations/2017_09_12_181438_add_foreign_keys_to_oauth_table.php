<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOauthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('oauth', function(Blueprint $table)
		{
			$table->foreign('user_id', 'fk_oauth_user1')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('oauth', function(Blueprint $table)
		{
			$table->dropForeign('fk_oauth_user1');
		});
	}

}
