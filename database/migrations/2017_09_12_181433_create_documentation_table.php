<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documentation', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('update_title', 100)->nullable();
			$table->timestamp('update_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->text('update_content', 16777215);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('documentation');
	}

}
