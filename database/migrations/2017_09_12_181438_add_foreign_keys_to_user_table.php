<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user', function(Blueprint $table)
		{
			$table->foreign('corporate_id', 'fk_user_corporate1')->references('id')->on('corporation')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('role_id', 'fk_user_role')->references('id')->on('role')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user', function(Blueprint $table)
		{
			$table->dropForeign('fk_user_corporate1');
			$table->dropForeign('fk_user_role');
		});
	}

}
