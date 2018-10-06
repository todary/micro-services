<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResultTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('result', function(Blueprint $table)
		{
			$table->foreign('source_id', 'fk_result_2')->references('id')->on('main_source')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('id', 'fk_result_entity')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('result', function(Blueprint $table)
		{
			$table->dropForeign('fk_result_2');
			$table->dropForeign('fk_result_entity');
		});
	}

}
