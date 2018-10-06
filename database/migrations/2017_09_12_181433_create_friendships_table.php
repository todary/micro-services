<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFriendshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friendships', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('person_id')->nullable()->index('person_id');
			$table->bigInteger('combination_id')->nullable();
			$table->boolean('direct_search')->default(0);
			$table->string('url1', 100)->index('url1');
			$table->string('url2', 100)->index('url2');
			$table->boolean('is_relative')->nullable();
			$table->string('source', 20)->nullable()->index('source');
			$table->string('name', 50)->nullable();
			$table->string('picture', 300)->nullable();
			$table->boolean('result_level')->nullable();
			$table->smallInteger('level')->nullable()->default(1);
			$table->integer('reason_flags')->nullable()->default(0)->index('reason_flags');
			$table->smallInteger('is_completed')->nullable();
			$table->unique(['person_id','url1','url2'], 'person_id_2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('friendships');
	}

}
