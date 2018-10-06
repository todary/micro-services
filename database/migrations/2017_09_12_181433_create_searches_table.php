<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('searches', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id')->index('invoice_id');
			$table->integer('user_id')->nullable()->index('fk_searches_user1_idx');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->float('unit_cost', 10, 0);
			$table->integer('total_searches');
			$table->integer('used_searched')->default(0);
			$table->dateTime('date');
			$table->dateTime('created')->nullable();
			$table->boolean('active')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('searches');
	}

}
