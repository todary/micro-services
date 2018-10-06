<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAnalystSearchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('analyst_searches', function(Blueprint $table)
		{
			$table->foreign('user_id', 'analyst_searches_ibfk_1')->references('id')->on('user')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('analyst_searches', function(Blueprint $table)
		{
			$table->dropForeign('analyst_searches_ibfk_1');
		});
	}

}
