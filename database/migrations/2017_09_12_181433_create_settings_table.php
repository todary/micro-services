<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('alias', 45);
			$table->string('key')->unique('key');
			$table->text('value');
			$table->string('title', 100);
			$table->text('description', 65535);
			$table->string('input_type', 45);
			$table->boolean('is_editable')->default(0);
			$table->integer('order');
			$table->integer('type')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
