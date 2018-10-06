<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationshipLinearTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relationship_linear', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('report_id')->unsigned();
			$table->bigInteger('relationship_id')->unsigned();
			$table->bigInteger('first_party')->unsigned();
			$table->bigInteger('second_party')->unsigned();
			$table->bigInteger('reason')->unsigned();

			//$table->unique(['report_id','first_party','second_party'], 'report_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('relationship_linear');
	}

}
