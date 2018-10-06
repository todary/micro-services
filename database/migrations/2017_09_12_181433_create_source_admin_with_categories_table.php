<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourceAdminWithCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('source_admin_with_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('source_admin_id')->unsigned();
			$table->integer('source_admin_category_id')->index('source_admin_category_id');
			$table->unique(['source_admin_id','source_admin_category_id'], 'source_admin_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('source_admin_with_categories');
	}

}
