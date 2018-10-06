<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRefundTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('refund', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('refund_type_id')->index('fk_refund_refund_type1_idx');
			$table->integer('invoice_id')->nullable();
			$table->integer('user_id')->index('fk_refund_user1_idx');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->float('value', 10, 0)->nullable();
			$table->text('reason', 65535);
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
		Schema::drop('refund');
	}

}
