<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYiiSessionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('YiiSession', function(Blueprint $table)
		{
			$table->char('id', 32)->primary();
			$table->integer('expire')->nullable()->index('expire');
			$table->binary('data')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('corporate_id')->nullable();
			$table->integer('last_activity')->index('last_activity');
			$table->string('user_agent')->nullable();
			$table->string('user_ip', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('YiiSession');
	}

}
