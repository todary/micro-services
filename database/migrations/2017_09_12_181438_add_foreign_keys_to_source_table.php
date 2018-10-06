<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('source', function(Blueprint $table)
		{
			$table->foreign('main_source_id', 'source_ibfk_1')->references('id')->on('main_source')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('source', function(Blueprint $table)
		{
			$table->dropForeign('source_ibfk_1');
		});
	}

}
