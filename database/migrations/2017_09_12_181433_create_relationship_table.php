<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationshipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relationship', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('report_id')->unsigned()->index('fk_friendship_1_idx');
			$table->enum('type', array('R2R','R2C','C2C','D2D','R2D','C2D'))->comment('(R) for result . (C) for combination , (D) for datapoint');
			$table->bigInteger('source_entity')->unsigned()->index('fk_friendship_2_idx');
			$table->bigInteger('target_entity')->unsigned()->index('fk_friendship_3_idx');
			$table->integer('reason');
			$table->unique(['report_id','source_entity','target_entity'], 'report_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('relationship');
	}

}
