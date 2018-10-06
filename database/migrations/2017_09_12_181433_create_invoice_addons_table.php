<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceAddonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_addons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('search_type')->comment('0=>Tracks,');
			$table->integer('user_id')->nullable()->index('user_id');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->integer('report_id')->nullable()->index('report_id');
			$table->integer('invoice_id')->nullable()->index('invoice_id');
			$table->integer('search_number');
			$table->float('total', 10, 0);
			$table->string('extra_data', 100)->nullable();
			$table->timestamp('updated')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_addons');
	}

}
