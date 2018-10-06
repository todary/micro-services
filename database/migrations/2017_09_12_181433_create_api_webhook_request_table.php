<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiWebhookRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_webhook_request', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('person_id');
			$table->string('url');
			$table->text('data', 16777215);
			$table->string('hash', 40)->nullable();
			$table->boolean('trials')->default(0)->index('trials');
			$table->dateTime('last_trial')->nullable();
			$table->text('last_reply', 65535)->nullable();
			$table->boolean('is_succeeded')->default(0)->index('is_succeeded');
			$table->dateTime('dateline')->index('dateline');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('api_webhook_request');
	}

}
