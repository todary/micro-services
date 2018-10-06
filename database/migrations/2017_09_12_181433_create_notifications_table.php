<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 100);
			$table->dateTime('last_check_date')->nullable();
			$table->dateTime('last_failure')->nullable();
			$table->boolean('last_status')->default(1);
			$table->dateTime('last_success')->nullable();
			$table->dateTime('last_date_mail_sent')->nullable();
			$table->text('additional_data', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
