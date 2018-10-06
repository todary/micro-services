<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCorporateDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('corporate_departments', function(Blueprint $table)
		{
			$table->foreign('corporate_id', 'corporate_departments_ibfk_1')->references('id')->on('corporation')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('corporate_departments', function(Blueprint $table)
		{
			$table->dropForeign('corporate_departments_ibfk_1');
		});
	}

}
