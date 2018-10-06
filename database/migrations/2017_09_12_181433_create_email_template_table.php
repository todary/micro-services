<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmailTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_template', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('email_layout_id')->index('email_layout_id');
			$table->string('alias', 45);
			$table->text('params', 65535);
			$table->string('subject', 150);
			$table->string('sender_email', 50);
			$table->string('sender_name', 50);
			$table->string('name', 50);
			$table->text('body', 65535);
			$table->boolean('active')->default(1);
			$table->text('description', 65535)->nullable();
			$table->text('actions', 65535)->nullable();
			$table->string('role_ids', 50)->default('[]');
			$table->timestamp('last_updates')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('email_template');
	}

}
