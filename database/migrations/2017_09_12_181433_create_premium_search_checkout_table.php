<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePremiumSearchCheckoutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('premium_search_checkout', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable();
			$table->integer('corporate_id')->nullable();
			$table->integer('report_id')->nullable();
			$table->bigInteger('order_number')->default(0);
			$table->boolean('transaction_type')->default(0)->comment('0=>PremiumSearch, 1=>Rescan');
			$table->integer('type')->comment('0=>Searches, 1=>Purchase, 3=>Both');
			$table->integer('premium_search_count');
			$table->integer('used_searches_count');
			$table->float('single_search_cost', 10, 0);
			$table->integer('single_search_searches_count');
			$table->float('total', 10, 0);
			$table->integer('used_count')->default(0);
			$table->boolean('active')->default(1);
			$table->dateTime('created')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('premium_search_checkout');
	}

}
