<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestIpHostStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_ip_host_status', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('request_ip_id')->index('request_ip_id_2');
			$table->integer('request_host_id')->index('request_host_id');
			$table->boolean('is_available')->index('is_available');
			$table->integer('fail_trials')->index('fail_trials');
			$table->dateTime('last_check')->nullable()->index('last_check');
			$table->decimal('last_usage', 20, 4)->default(0.0000)->index('last_usage');
			$table->unique(['request_ip_id','request_host_id'], 'request_ip_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('request_ip_host_status');
	}

}
