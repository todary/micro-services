<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoicePaymentFailureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_payment_failure', function(Blueprint $table)
		{
			$table->foreign('invoice_id', 'invoice_payment_failure_ibfk_1')->references('id')->on('invoice')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'invoice_payment_failure_ibfk_2')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('auth_id', 'invoice_payment_failure_ibfk_3')->references('id')->on('authorization')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_payment_failure', function(Blueprint $table)
		{
			$table->dropForeign('invoice_payment_failure_ibfk_1');
			$table->dropForeign('invoice_payment_failure_ibfk_2');
			$table->dropForeign('invoice_payment_failure_ibfk_3');
		});
	}

}
