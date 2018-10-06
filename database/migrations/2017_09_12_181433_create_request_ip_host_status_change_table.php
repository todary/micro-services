<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestIpHostStatusChangeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_ip_host_status_change', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('request_ip_host_status_id')->index('request_ip_host_status_id');
			$table->boolean('is_available')->index('is_available');
			$table->integer('fail_trials')->index('fail_trials');
			$table->integer('count');
			$table->dateTime('start_date');
			$table->dateTime('end_date')->nullable();
			$table->text('notes', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('request_ip_host_status_change');
	}

}
