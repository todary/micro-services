<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorizationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('authorization', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('fk_authorization_user1_idx');
			$table->integer('corporate_id')->nullable()->index('corporate_id');
			$table->integer('payment_method_id')->index('fk_authorization_authorization_type1_idx');
			$table->string('name', 45)->nullable();
			$table->string('card_number', 20)->nullable();
			$table->string('card_type', 20)->nullable();
			$table->string('billing_zip', 20)->nullable();
			$table->string('exp_date', 10)->nullable();
			$table->string('address', 150)->nullable();
			$table->string('city', 50)->default('');
			$table->string('state', 45)->nullable();
			$table->string('attn', 45)->nullable();
			$table->string('transaction_id', 100)->nullable();
			$table->string('auth_customer_id', 45)->nullable();
			$table->string('auth_customer_profile_id', 150)->nullable();
			$table->boolean('is_disabled')->default(0);
			$table->boolean('is_deleted')->default(0);
			$table->boolean('is_approved')->default(0);
			$table->float('balance', 10, 0)->default(0);
			$table->string('account', 100)->nullable();
			$table->dateTime('updated')->nullable();
			$table->dateTime('created')->nullable();
			$table->dateTime('last_failure_notification')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('authorization');
	}

}
