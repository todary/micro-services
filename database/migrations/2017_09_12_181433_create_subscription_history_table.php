<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_history', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('corporate_id')->index('corporate_id');
			$table->integer('plan_id')->index('plan_id');
			$table->integer('plan_price_id');
			$table->integer('plan_addon_id')->nullable()->index('plan_addon_id');
			$table->boolean('is_annual');
			$table->integer('user_id')->nullable()->index('user_id');
			$table->string('action', 20);
			$table->string('notes', 100)->nullable();
			$table->timestamp('dateline')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscription_history');
	}

}
