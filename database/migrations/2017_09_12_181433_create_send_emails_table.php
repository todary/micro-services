<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSendEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('send_emails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('mail')->nullable();
			$table->integer('count_to');
			$table->dateTime('created')->index('created');
			$table->integer('priority')->default(50)->index('priority');
			$table->boolean('sent')->default(0)->index('sent');
			$table->boolean('has_error');
			$table->boolean('trials')->default(0);
			$table->boolean('is_sending')->default(0);
			$table->dateTime('last_send_trial')->nullable();
			$table->text('send_to', 65535);
			$table->string('sendfrom');
			$table->string('sendername')->nullable();
			$table->string('subject');
			$table->text('body', 16777215);
			$table->text('cc', 65535)->nullable();
			$table->text('bcc', 65535)->nullable();
			$table->dateTime('date_sent')->nullable()->index('date_sent');
			$table->integer('person_id')->nullable();
			$table->string('message_id', 100)->nullable()->index('message_id');
			$table->string('status', 30)->nullable();
			$table->integer('email_template_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('send_emails');
	}

}
