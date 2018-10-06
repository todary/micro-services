<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSubscriptionHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('subscription_history', function(Blueprint $table)
		{
			$table->foreign('corporate_id', 'subscription_history_ibfk_1')->references('id')->on('corporation')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('plan_id', 'subscription_history_ibfk_2')->references('id')->on('subscription_plans')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('plan_addon_id', 'subscription_history_ibfk_3')->references('id')->on('subscription_plan_addon')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id', 'subscription_history_ibfk_4')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('subscription_history', function(Blueprint $table)
		{
			$table->dropForeign('subscription_history_ibfk_1');
			$table->dropForeign('subscription_history_ibfk_2');
			$table->dropForeign('subscription_history_ibfk_3');
			$table->dropForeign('subscription_history_ibfk_4');
		});
	}

}
