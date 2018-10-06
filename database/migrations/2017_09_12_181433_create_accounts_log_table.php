<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts_log', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->nullable();
			$table->bigInteger('combination_id')->nullable();
			$table->string('source', 30);
			$table->integer('account_id');
			$table->string('type', 10);
			$table->string('url', 300);
			$table->text('response', 65535);
			$table->text('content', 16777215);
			$table->boolean('status');
			$table->timestamp('dateline')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts_log');
	}

}
