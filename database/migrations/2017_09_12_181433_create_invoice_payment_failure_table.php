<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicePaymentFailureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_payment_failure', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id')->index('invoice_id');
			$table->integer('user_id')->nullable()->index('user_id');
			$table->integer('auth_id')->index('auth_id');
			$table->string('reason', 300)->nullable();
			$table->text('response', 16777215)->nullable();
			$table->dateTime('dateline');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_payment_failure');
	}

}
