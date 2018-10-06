<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entity', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('report_id')->unsigned();
			$table->enum('type', array('result','sub_result','datapoint','combination'));
			$table->boolean('deleted')->default(0)->index('deleted');
			$table->boolean('hidden')->default(0)->index('hidden');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entity');
	}

}
