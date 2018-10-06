<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPlanAddonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_plan_addon', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('addon_title', 50);
			$table->integer('subscription_plan_id')->index('subscription_plan_id');
			$table->integer('searches_count');
			$table->integer('premiums_count');
			$table->float('monthly_price', 10, 0);
			$table->float('annually_price', 10, 0);
			$table->float('extra_normal_search_price', 10, 0);
			$table->float('extra_premium_search_price', 10, 0);
			$table->boolean('is_active')->index('is_active');
			$table->boolean('visible_to_users')->default(0);
			$table->boolean('is_deleted')->default(0);
			$table->boolean('invoice_combine_with_plan')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscription_plan_addon');
	}

}
