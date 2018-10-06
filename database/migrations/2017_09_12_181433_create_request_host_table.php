<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestHostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_host', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('host_name', 100);
			$table->boolean('auto_proxy_assign')->default(1);
			$table->boolean('screenshot_auto_proxy_assign')->default(0);
			$table->unique(['host_name','screenshot_auto_proxy_assign'], 'host_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('request_host');
	}

}
