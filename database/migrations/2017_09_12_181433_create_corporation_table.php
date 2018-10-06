<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCorporationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('corporation', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('service_id')->index('fk_corporation_service1_idx');
			$table->string('name', 100)->nullable();
			$table->string('email', 150)->nullable()->unique('email');
			$table->string('website', 50);
			$table->string('role', 50)->nullable();
			$table->string('company_size', 15)->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('logo')->nullable();
			$table->string('tax_id', 45)->nullable();
			$table->string('header_color', 10)->nullable();
			$table->string('header_font_color', 10)->nullable();
			$table->integer('max_quota');
			$table->integer('allowed_searches')->default(0);
			$table->integer('used_searched')->default(0);
			$table->dateTime('payg_start')->nullable();
			$table->dateTime('payg_last_invoice_date')->nullable();
			$table->integer('payg_month_searches')->default(0);
			$table->integer('payg_all_searches')->default(0);
			$table->boolean('is_auto_refill')->default(0);
			$table->integer('auto_refill_qty')->default(1);
			$table->dateTime('free_trial_start')->nullable();
			$table->dateTime('free_trial_end')->nullable();
			$table->integer('free_trial_searches')->default(0);
			$table->integer('downloads_count')->default(0);
			$table->boolean('membership_status')->default(1)->comment('1=>active, 2=>inactive');
			$table->string('auth_customer_id', 45)->nullable();
			$table->boolean('active')->default(1);
			$table->float('total_billed', 10, 0)->default(0);
			$table->float('total_owed', 10, 0)->default(0);
			$table->boolean('waiting_change_service')->default(0);
			$table->boolean('is_deleted')->default(0)->index('is_deleted');
			$table->boolean('direct_to_pdf')->default(0);
			$table->boolean('show_file_number')->nullable()->default(0);
			$table->boolean('enable_premium_search')->default(0);
			$table->integer('session_lifetime')->nullable()->comment('In hours');
			$table->boolean('tracking_email_notification')->default(0);
			$table->boolean('tracking_all_tracking')->default(0);
			$table->boolean('allow_employee_change_dept')->default(1);
			$table->boolean('white_label')->default(0);
			$table->boolean('show_behavior_flags')->default(1);
			$table->boolean('invoice_type')->default(0)->index('invoice_type')->comment('0=>Per Cycle, 1=>Per Report');
			$table->boolean('show_searched_name_in_invoice')->default(1);
			$table->integer('subscription_plan')->nullable()->index('subscription_plan');
			$table->boolean('subscription_plan_is_annual')->default(0);
			$table->integer('subscription_plan_price_id')->nullable()->index('subscription_plan_price_id');
			$table->integer('subscription_plan_addon_id')->nullable()->index('subscription_plan_addon_id');
			$table->dateTime('subscription_plan_monthly_start')->nullable();
			$table->dateTime('subscription_plan_annual_start')->nullable();
			$table->dateTime('subscription_plan_end')->nullable();
			$table->date('subscription_last_invoice')->nullable();
			$table->boolean('high_search_usage')->default(0);
			$table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('sales_analyst_id')->nullable();
			$table->dateTime('upgrated_at')->nullable();
			$table->boolean('pending_is_annual')->nullable();
			$table->integer('pending_plan_id')->nullable()->index('pending_plan_id');
			$table->integer('pending_price_id')->nullable()->index('pending_price_id');
			$table->integer('pending_addon_id')->nullable()->index('pending_addon_id');
			$table->dateTime('pending_date')->nullable();
			$table->integer('free_premium_searches')->default(0);
			$table->boolean('is_trial_extended')->default(0);
			$table->dateTime('all_lastvisit_at')->nullable();
			$table->dateTime('all_lastsearch_at')->nullable();
			$table->string('inputs_limits', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('corporation');
	}

}
