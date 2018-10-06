<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProgressDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('progress_data', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->unsigned()->index('person_id');
			$table->string('type', 20)->index('type');
			$table->string('main_value', 100)->nullable();
			$table->char('hash', 32);
			$table->char('data_key', 32);
			$table->string('assoc_profile', 75)->nullable();
			$table->bigInteger('res')->unsigned()->nullable();
			$table->bigInteger('parent_comb')->unsigned()->nullable();
			$table->string('combinations_ids', 50)->nullable();
			$table->text('data_json', 16777215)->nullable();
			$table->boolean('pending')->default(0)->comment('0=>notHidden,1=>hidden');
			$table->boolean('copied_from_rescan')->default(0)->index('copied_from_rescan')->comment('1 => old result, 0 => new result');
			$table->boolean('is_verified')->default(0);
			$table->text('combined_data', 65535)->nullable();
			$table->smallInteger('is_deleted')->default(0)->index('is_deleted')->comment('0=>notDeleted,1=>deleted,2=>hidden');
			$table->bigInteger('delete_id')->nullable();
			$table->integer('ui_index')->default(0);
			$table->primary(['id','person_id']);
			$table->unique(['person_id','type','hash'], 'person_id_2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('progress_data');
	}

}
