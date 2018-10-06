<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_service', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('service_descr', 100)->nullable();
			$table->integer('invoice_id')->index('fk_invoice_has_service_invoice1_idx');
			$table->integer('service_id')->index('fk_invoice_has_service_service1_idx');
			$table->float('qty', 10, 0)->nullable();
			$table->float('unit_cost', 10, 0);
			$table->float('total', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_service');
	}

}
