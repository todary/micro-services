<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestIpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_ip', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ip', 30)->index('ip');
			$table->boolean('is_proxy')->index('is_proxy');
			$table->string('notes', 50)->nullable();
			$table->integer('port');
			$table->string('username', 30);
			$table->string('password', 30);
			$table->boolean('is_active')->default(1)->index('is_active');
			$table->boolean('is_capture_active')->index('is_capture_active');
			$table->unique(['ip','is_proxy','port'], 'ip_2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('request_ip');
	}

}
