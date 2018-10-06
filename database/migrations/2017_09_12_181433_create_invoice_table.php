<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable()->index('fk_invoice_user1_idx');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->integer('invoice_status_id')->index('fk_invoice_invoice_status1_idx');
			$table->integer('payment_method_id')->index('fk_invoice_payment_method1_idx');
			$table->integer('transaction_id')->nullable()->index('fk_invoice_transaction1_idx');
			$table->bigInteger('order_number')->default(0);
			$table->integer('searches_number')->nullable();
			$table->float('total', 10, 0)->nullable();
			$table->float('original_total', 10, 0)->nullable();
			$table->string('adjustment_reason', 100)->nullable();
			$table->dateTime('invoice_date');
			$table->dateTime('due_date');
			$table->dateTime('created')->nullable();
			$table->boolean('is_auto_refill')->default(0);
			$table->boolean('is_end_of_cycle')->default(0);
			$table->boolean('is_deleted')->default(0);
			$table->boolean('is_admin_deleted')->default(0);
			$table->integer('main_service_id')->nullable()->index('main_service_id');
			$table->integer('sub_plan_id')->nullable();
			$table->integer('sub_price_id')->nullable();
			$table->integer('sub_addon_id')->nullable();
			$table->boolean('sub_is_annual')->default(0);
			$table->string('invoice_footer_label', 50)->nullable();
			$table->boolean('is_subscription_pending')->default(0);
			$table->boolean('is_subscription_pending_processed')->default(0);
			$table->dateTime('last_payment_failed_trial')->nullable()->index('last_payment_failed_trial');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice');
	}

}
