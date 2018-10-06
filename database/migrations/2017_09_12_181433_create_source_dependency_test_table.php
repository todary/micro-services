<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourceDependencyTestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('source_dependency_test', function(Blueprint $table)
		{
			$table->string('dependee', 50)->index('dependee');
			$table->string('dependent', 50)->index('dependent');
			$table->string('notes', 50)->nullable();
			$table->primary(['dependee','dependent']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('source_dependency_test');
	}

}
