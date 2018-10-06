<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRelationshipLinearTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('relationship_linear', function(Blueprint $table)
		{
			$table->foreign('relationship_id', 'fk_friendship_linear_2')->references('id')->on('relationship')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('first_party', 'fk_friendship_linear_3')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('second_party', 'fk_friendship_linear_4')->references('id')->on('entity')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('relationship_linear', function(Blueprint $table)
		{
			$table->dropForeign('fk_friendship_linear_2');
			$table->dropForeign('fk_friendship_linear_3');
			$table->dropForeign('fk_friendship_linear_4');
		});
	}

}
