<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAuthorizationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authorization', function(Blueprint $table)
		{
			$table->foreign('corporate_id', 'authorization_ibfk_1')->references('id')->on('corporation')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('payment_method_id', 'fk_authorization_authorization_type1')->references('id')->on('payment_method')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'fk_authorization_user1')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('authorization', function(Blueprint $table)
		{
			$table->dropForeign('authorization_ibfk_1');
			$table->dropForeign('fk_authorization_authorization_type1');
			$table->dropForeign('fk_authorization_user1');
		});
	}

}
