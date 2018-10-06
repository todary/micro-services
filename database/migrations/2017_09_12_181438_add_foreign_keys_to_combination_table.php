<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCombinationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('combination', function(Blueprint $table)
		{
			$table->foreign('source_id', 'fk_combination_2')->references('id')->on('source')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('id', 'fk_combination_entity1')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('combination', function(Blueprint $table)
		{
			$table->dropForeign('fk_combination_2');
			$table->dropForeign('fk_combination_entity1');
		});
	}

}
