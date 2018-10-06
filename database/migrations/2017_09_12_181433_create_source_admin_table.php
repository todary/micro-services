<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourceAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('source_admin', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('source', 150)->unique('source');
			$table->integer('free_status')->default(0);
			$table->integer('paid_status')->default(0);
			$table->integer('free_number_of_req')->nullable();
			$table->integer('paid_number_of_req')->nullable();
			$table->boolean('is_visible')->default(1);
			$table->boolean('visible_in_rescan')->default(0);
			$table->text('notes', 65535);
			$table->string('filter_source', 100);
			$table->string('display_name', 100);
			$table->integer('source_admin_category_id')->nullable();
			$table->boolean('is_system')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('source_admin');
	}

}
