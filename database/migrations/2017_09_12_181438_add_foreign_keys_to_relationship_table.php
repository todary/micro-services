<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRelationshipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('relationship', function(Blueprint $table)
		{
			$table->foreign('source_entity', 'fk_friendship_2')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('target_entity', 'fk_friendship_3')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('relationship', function(Blueprint $table)
		{
			$table->dropForeign('fk_friendship_2');
			$table->dropForeign('fk_friendship_3');
		});
	}

}
