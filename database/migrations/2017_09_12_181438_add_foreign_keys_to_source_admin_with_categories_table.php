<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSourceAdminWithCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('source_admin_with_categories', function(Blueprint $table)
		{
			$table->foreign('source_admin_id', 'source_admin')->references('id')->on('source_admin')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('source_admin_category_id', 'source_admin_with_categories_ibfk_1')->references('id')->on('source_admin_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('source_admin_with_categories', function(Blueprint $table)
		{
			$table->dropForeign('source_admin');
			$table->dropForeign('source_admin_with_categories_ibfk_1');
		});
	}

}
