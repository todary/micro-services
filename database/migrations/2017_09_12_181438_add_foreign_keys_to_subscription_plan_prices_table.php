<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSubscriptionPlanPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('subscription_plan_prices', function(Blueprint $table)
		{
			$table->foreign('subscription_plan_id', 'subscription_plan_prices_ibfk_1')->references('id')->on('subscription_plans')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('subscription_plan_prices', function(Blueprint $table)
		{
			$table->dropForeign('subscription_plan_prices_ibfk_1');
		});
	}

}
