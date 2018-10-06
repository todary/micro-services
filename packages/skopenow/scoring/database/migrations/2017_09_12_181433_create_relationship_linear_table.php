<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationshipLinearTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relationship_linear', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('report_id')->unsigned()->index('fk_friendship_linear_1_idx');
			$table->bigInteger('relationship_id')->unsigned()->index('fk_friendship_linear_2_idx');
			$table->bigInteger('first_party')->unsigned()->index('fk_friendship_linear_3_idx');
			$table->bigInteger('second_party')->unsigned()->index('fk_friendship_linear_4_idx');
			$table->unique(['report_id','first_party','second_party'], 'report_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('relationship_linear');
	}

}
