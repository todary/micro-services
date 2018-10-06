<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('function', 50);
			$table->text('parent_function', 65535);
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('logged_in_user')->nullable();
			$table->string('ip', 50);
			$table->text('url', 65535);
			$table->integer('invoice_id');
			$table->string('amount', 20);
			$table->integer('invoice_user_id')->nullable();
			$table->integer('invoice_corp_id')->nullable();
			$table->integer('invoice_type');
			$table->integer('payment_method');
			$table->string('invoice_user_email', 250)->nullable();
			$table->string('invoice_corp_email', 250)->nullable();
			$table->integer('invoice_status');
			$table->integer('invoice_user_service_id');
			$table->string('status', 20);
			$table->text('reason', 65535);
			$table->text('other', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices_log');
	}

}
