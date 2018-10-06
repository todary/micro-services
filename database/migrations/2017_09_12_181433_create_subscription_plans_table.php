<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_plans', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('plan_name', 30);
			$table->string('plan_title', 30);
			$table->boolean('is_payg')->default(0);
			$table->float('normal_search_discount', 10, 0)->nullable()->comment('DEPRECATED');
			$table->float('premium_search_discount', 10, 0)->nullable()->comment('DEPRECATED');
			$table->float('monthly_fees_per_full_employee', 10, 0)->nullable()->comment('DEPRECATED');
			$table->float('monthly_fees_per_partial_employee', 10, 0)->nullable()->comment('DEPRECATED');
			$table->integer('max_users')->comment('0 = unlimited');
			$table->integer('max_searches');
			$table->integer('max_premium');
			$table->char('color', 6);
			$table->boolean('has_addons');
			$table->boolean('show_in_pricing')->index('show_in_pricing');
			$table->boolean('show_in_registration');
			$table->boolean('is_active')->index('is_active');
			$table->boolean('is_most_popular');
			$table->integer('max_concurrent_searches')->default(0);
			$table->smallInteger('display_order');
			$table->boolean('is_deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscription_plans');
	}

}
