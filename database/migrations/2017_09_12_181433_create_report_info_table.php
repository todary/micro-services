<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('report_info', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('report_id')->unique('report_id');
			$table->string('report_by', 300);
			$table->string('company_name', 350);
			$table->integer('search_date');
			$table->string('subject_name', 500);
			$table->string('current_address', 100);
			$table->string('date_of_birth', 200);
			$table->text('email', 65535);
			$table->text('phone', 65535);
			$table->string('occupation', 300)->nullable()->default('[]');
			$table->string('school', 300)->nullable()->default('[]');
			$table->text('usernames', 65535);
			$table->text('relatives', 65535);
			$table->text('previous_locations', 65535);
			$table->integer('social_footprint')->default(0);
			$table->string('options', 400);
			$table->text('additional_notes', 65535);
			$table->dateTime('additional_notes_update_date')->nullable();
			$table->boolean('additional_notes_mail_is_sent')->default(0);
			$table->text('profiles_data', 65535);
			$table->text('phones_data', 65535);
			$table->text('relatives_data', 65535);
			$table->text('emails_data', 65535);
			$table->text('addresses_data', 65535);
			$table->integer('data_updated')->default(0);
			$table->integer('report_changed')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('report_info');
	}

}
