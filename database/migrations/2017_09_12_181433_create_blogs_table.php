<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blogs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->string('title', 50);
			$table->string('alias', 70);
			$table->string('subtitle', 200);
			$table->text('text', 65535);
			$table->string('placeholder_image', 250);
			$table->string('author_name', 50);
			$table->text('tags', 65535);
			$table->integer('views_number');
			$table->integer('shares_number');
			$table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blogs');
	}

}
