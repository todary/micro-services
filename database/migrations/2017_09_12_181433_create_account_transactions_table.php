<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_transactions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('account_id')->index('account_id');
			$table->integer('invoice_id')->nullable()->index('invoice_id');
			$table->string('type', 2)->index('type')->comment('d=debit, c= credit');
			$table->string('transaction_type', 20)->comment('(pur_search, use_search, pur_prem_search, conv_search_prem, use_prem_search, add_rescan, use_rescan, use_trial, use_payg)');
			$table->integer('search_id')->nullable()->index('search_id');
			$table->integer('searches_count_before');
			$table->integer('searches_count_after');
			$table->integer('searches_count_used_before');
			$table->integer('searches_count_used_after');
			$table->integer('rescan_count_before');
			$table->integer('rescan_count_after');
			$table->integer('rescan_count_used_before');
			$table->integer('rescan_count_used_after');
			$table->integer('premium_search_count_before');
			$table->integer('premium_search_count_after');
			$table->integer('premium_search_count_used_before');
			$table->integer('premium_search_count_used_after');
			$table->integer('pay_as_you_go_searches_before');
			$table->integer('pay_as_you_go_searches_after');
			$table->integer('pay_as_you_go_searches_month_used_before');
			$table->integer('pay_as_you_go_searches_month_used_after');
			$table->integer('free_trial_searches_count_before');
			$table->integer('free_trial_searches_count_after');
			$table->integer('subscription_month_normal_searches_before')->default(0);
			$table->integer('subscription_month_normal_searches_after')->default(0);
			$table->integer('subscription_month_premium_searches_before')->default(0);
			$table->integer('subscription_month_premium_searches_after')->default(0);
			$table->integer('subscription_total_normal_searches_before')->default(0);
			$table->integer('subscription_total_normal_searches_after')->default(0);
			$table->integer('subscription_total_premium_searches_before')->default(0);
			$table->integer('subscription_total_premium_searches_after')->default(0);
			$table->integer('subscription_extra_normal_searches_before')->default(0);
			$table->integer('subscription_extra_premium_searches_before')->default(0);
			$table->integer('subscription_extra_normal_searches_after')->default(0);
			$table->integer('subscription_extra_premium_searches_after')->default(0);
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
		Schema::drop('account_transactions');
	}

}
