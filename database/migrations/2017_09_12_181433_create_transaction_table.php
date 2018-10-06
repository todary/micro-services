<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaction', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('authorization_id')->index('fk_transaction_authorization1_idx');
			$table->integer('transaction_type_id')->index('fk_transaction_transaction_type1_idx');
			$table->integer('refund_id')->nullable()->index('fk_transaction_refund1_idx');
			$table->float('amount', 10, 0)->default(0);
			$table->string('transaction_id', 45)->nullable();
			$table->string('account', 100)->nullable();
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
		Schema::drop('transaction');
	}

}
