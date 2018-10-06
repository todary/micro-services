<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserBannedDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_banned_domains', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('user.user_id');
			$table->string('url', 200);
			$table->string('source', 200)->nullable();
			$table->dateTime('dateline');
			$table->unique(['user_id','url'], 'user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_banned_domains');
	}

}
