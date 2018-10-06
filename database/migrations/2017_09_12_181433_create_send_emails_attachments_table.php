<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSendEmailsAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('send_emails_attachments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('send_emails_id')->unsigned()->index('send_emails_id');
			$table->string('filepath')->nullable();
			$table->string('filename')->nullable();
			$table->string('name')->nullable();
			$table->string('encoding')->nullable();
			$table->string('type')->nullable();
			$table->boolean('isStringAttachment');
			$table->string('method')->nullable();
			$table->string('cid')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('send_emails_attachments');
	}

}
