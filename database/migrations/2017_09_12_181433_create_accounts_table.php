<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable()->index('user_id');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->integer('searches_count')->default(0);
			$table->integer('searches_count_used')->default(0);
			$table->integer('rescan_count')->default(0);
			$table->integer('rescan_count_used')->default(0);
			$table->integer('premium_search_count')->default(0);
			$table->integer('premium_search_count_used')->default(0);
			$table->integer('pay_as_you_go_searches')->default(0);
			$table->integer('pay_as_you_go_searches_month_used')->default(0);
			$table->integer('free_trial_searches_count')->default(0);
			$table->integer('subscription_month_normal_searches')->default(0);
			$table->integer('subscription_month_premium_searches')->default(0);
			$table->integer('subscription_extra_normal_searches')->default(0);
			$table->integer('subscription_extra_premium_searches')->default(0);
			$table->integer('subscription_total_normal_searches')->default(0);
			$table->integer('subscription_total_premium_searches')->default(0);
			$table->boolean('added_inactive_credit_count')->default(0);
			$table->dateTime('last_added_inactive_credit')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}

}
