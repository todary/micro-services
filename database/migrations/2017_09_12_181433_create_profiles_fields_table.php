<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profiles_fields', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('varname', 50);
			$table->string('title');
			$table->string('field_type', 50);
			$table->string('field_size', 15)->default('0');
			$table->string('field_size_min', 15)->default('0');
			$table->integer('required')->default(0);
			$table->string('match')->default('');
			$table->string('range')->default('');
			$table->string('error_message')->default('');
			$table->string('other_validator', 5000)->default('');
			$table->string('default')->default('');
			$table->string('widget')->default('');
			$table->string('widgetparams', 5000)->default('');
			$table->integer('position')->default(0);
			$table->integer('visible')->default(0);
			$table->index(['varname','widget','visible'], 'varname');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profiles_fields');
	}

}
