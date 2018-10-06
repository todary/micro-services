<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSendEmailsStringAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('send_emails_string_attachments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('file_path', 250)->index('file_path');
			$table->binary('content', 16777215);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('send_emails_string_attachments');
	}

}
