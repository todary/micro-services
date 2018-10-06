<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserExcludedSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_excluded_source', function(Blueprint $table)
		{
			$table->foreign('user_id', 'user_excluded_source_ibfk_1')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('source_id', 'user_excluded_source_ibfk_2')->references('id')->on('source_admin')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_excluded_source', function(Blueprint $table)
		{
			$table->dropForeign('user_excluded_source_ibfk_1');
			$table->dropForeign('user_excluded_source_ibfk_2');
		});
	}

}
