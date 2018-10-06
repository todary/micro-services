<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_menu', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title', 50);
			$table->string('url', 200);
			$table->boolean('status')->default(0);
			$table->integer('sortid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_menu');
	}

}
