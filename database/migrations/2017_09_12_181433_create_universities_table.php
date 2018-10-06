<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUniversitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('universities', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('institution_id')->nullable()->default(0);
			$table->string('institution_name', 200)->default('0');
			$table->string('institution_address', 100)->default('0');
			$table->string('institution_city', 50)->default('0');
			$table->string('institution_state', 50)->default('0');
			$table->string('institution_zip', 20)->default('0');
			$table->string('Institution_phone', 50)->default('0');
			$table->string('institution_ope_id', 20)->default('0');
			$table->string('institution_ipeds_unit_id', 20)->default('0');
			$table->string('institution_web_address', 100)->default('0');
			$table->integer('campus_id')->default(0);
			$table->string('campus_name', 200)->default('0');
			$table->string('campus_address', 200)->default('0');
			$table->string('campus_city', 50)->default('0');
			$table->string('campus_state', 20)->default('0');
			$table->string('campus_zip', 20)->default('0');
			$table->string('campus_ipeds_unit_id', 10)->default('0');
			$table->string('accreditation_type', 50)->default('0');
			$table->string('agency_name', 200)->default('0');
			$table->string('agency_status', 50)->default('0');
			$table->string('program_name', 350)->default('0');
			$table->string('accreditation_status', 25)->default('0');
			$table->string('accreditation_date_type', 20)->default('0');
			$table->string('periods', 50)->default('0');
			$table->string('last_action', 50)->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('universities');
	}

}
