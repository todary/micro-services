<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('social_account', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('source', 20);
			$table->boolean('type')->default(0)->comment('0=Scrap, 1=API');
			$table->string('username', 50);
			$table->string('password', 50);
			$table->string('verification_answer', 50)->nullable();
			$table->string('associated_proxy_ip', 20)->nullable();
			$table->integer('associated_proxy_port')->nullable();
			$table->dateTime('last_check')->nullable();
			$table->dateTime('last_usage')->nullable();
			$table->boolean('is_active')->default(1);
			$table->boolean('is_available')->default(1);
			$table->text('data', 65535)->nullable();
			$table->integer('fail_trials')->default(0);
			$table->string('reason', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('social_account');
	}

}
