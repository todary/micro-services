<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mail_config', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('protocol', 20);
			$table->string('host', 500);
			$table->string('username', 500);
			$table->string('password', 500);
			$table->integer('port');
			$table->boolean('smtp_auth');
			$table->string('smtp_secure', 20);
			$table->boolean('smtp_auto');
			$table->boolean('available')->default(0);
			$table->boolean('is_active')->default(0);
			$table->integer('try');
			$table->dateTime('last_check');
			$table->timestamp('last_usage')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mail_config');
	}

}
