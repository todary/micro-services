<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transaction', function(Blueprint $table)
		{
			$table->foreign('transaction_type_id', 'transaction_ibfk_1')->references('id')->on('transaction_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('refund_id', 'transaction_ibfk_2')->references('id')->on('refund')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('authorization_id', 'transaction_ibfk_3')->references('id')->on('authorization')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transaction', function(Blueprint $table)
		{
			$table->dropForeign('transaction_ibfk_1');
			$table->dropForeign('transaction_ibfk_2');
			$table->dropForeign('transaction_ibfk_3');
		});
	}

}
