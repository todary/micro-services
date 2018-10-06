<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCorporationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('corporation', function(Blueprint $table)
		{
			$table->foreign('subscription_plan', 'corporation_ibfk_1')->references('id')->on('subscription_plans')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('subscription_plan_price_id', 'corporation_ibfk_2')->references('id')->on('subscription_plan_prices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('subscription_plan_addon_id', 'corporation_ibfk_3')->references('id')->on('subscription_plan_addon')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('pending_plan_id', 'corporation_ibfk_4')->references('id')->on('subscription_plans')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('pending_price_id', 'corporation_ibfk_5')->references('id')->on('subscription_plan_prices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('pending_addon_id', 'corporation_ibfk_6')->references('id')->on('subscription_plan_addon')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('service_id', 'fk_corporation_service1')->references('id')->on('service')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('corporation', function(Blueprint $table)
		{
			$table->dropForeign('corporation_ibfk_1');
			$table->dropForeign('corporation_ibfk_2');
			$table->dropForeign('corporation_ibfk_3');
			$table->dropForeign('corporation_ibfk_4');
			$table->dropForeign('corporation_ibfk_5');
			$table->dropForeign('corporation_ibfk_6');
			$table->dropForeign('fk_corporation_service1');
		});
	}

}
