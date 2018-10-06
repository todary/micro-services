<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGoogleIpStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('google_ip_status', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ip', 30)->index('ip');
			$table->boolean('is_proxy')->index('is_proxy');
			$table->integer('port');
			$table->string('notes', 50)->nullable();
			$table->string('username', 30);
			$table->string('password', 30);
			$table->boolean('is_available')->index('is_available');
			$table->integer('fail_trials')->index('fail_trials');
			$table->boolean('is_active')->default(1)->index('is_active');
			$table->boolean('is_capture_active')->index('is_capture_active');
			$table->dateTime('last_check')->index('last_check');
			$table->decimal('last_search', 20, 4)->index('last_search');
			$table->boolean('in_use')->default(0)->index('in_use');
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
		Schema::drop('google_ip_status');
	}

}
