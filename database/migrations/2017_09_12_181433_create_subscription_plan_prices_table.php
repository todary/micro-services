<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPlanPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_plan_prices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('subscription_plan_id')->index('subscription_plan_id');
			$table->float('monthly_price', 10, 0);
			$table->float('annually_price', 10, 0);
			$table->float('extra_normal_search_price', 10, 0);
			$table->float('extra_premium_search_price', 10, 0);
			$table->dateTime('start_date');
			$table->dateTime('end_date')->nullable();
			$table->boolean('is_active')->index('is_active');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscription_plan_prices');
	}

}
