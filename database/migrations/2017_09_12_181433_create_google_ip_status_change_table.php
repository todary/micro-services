<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGoogleIpStatusChangeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('google_ip_status_change', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('google_ip_status_id');
			$table->boolean('is_available');
			$table->integer('fail_trials');
			$table->integer('count');
			$table->dateTime('start_date');
			$table->dateTime('end_date')->nullable();
			$table->string('notes', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('google_ip_status_change');
	}

}
