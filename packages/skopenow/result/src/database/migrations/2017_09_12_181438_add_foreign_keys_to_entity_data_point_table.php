<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEntityDataPointTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entity_data_point', function(Blueprint $table)
		{
			$table->foreign('entity_id', 'fk_entity_data_point_2')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entity_data_point', function(Blueprint $table)
		{
			$table->dropForeign('fk_entity_data_point_2');
		});
	}

}
