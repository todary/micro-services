<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourcesSortTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sources_sort', function(Blueprint $table)
		{
			$table->string('source', 150)->default('')->primary();
			$table->integer('list_order')->default(0)->index('list_order');
			$table->integer('search_order')->default(0)->index('search_order');
			$table->boolean('is_active')->default(1)->index('is_active');
			$table->string('notes', 150)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sources_sort');
	}

}
