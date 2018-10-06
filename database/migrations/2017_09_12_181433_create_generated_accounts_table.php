<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneratedAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('generated_accounts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 20)->default('facebook');
			$table->string('email', 128)->unique('email');
			$table->string('password', 128);
			$table->boolean('blocked')->nullable();
			$table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->date('dob')->nullable();
			$table->boolean('registered')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('generated_accounts');
	}

}
