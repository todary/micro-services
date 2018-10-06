<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubResultTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_result', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('entity_id')->unsigned()->nullable()->index('entity_id');
			$table->bigInteger('report_id')->unsigned()->index('fk_sub_result_1_idx');
			$table->bigInteger('result_id')->unsigned()->index('fk_sub_result_2_idxx');
			$table->enum('type', array('page','video','image','post','likes','other','about','friends','photos','events','skills','Profile','groups','music','reviews','sports','photo','app','liked','tagged','commented','uploaded','photos of','visited'));
			$table->string('url', 700);
			$table->string('unique_content', 350);
			$table->string('alternative_unique_content', 350)->nullable();
			$table->integer('date')->default(0);
			$table->boolean('is_deleted')->nullable()->default(0);
			$table->boolean('deletion_type')->nullable();
			$table->boolean('is_manual')->nullable()->default(0);
			$table->boolean('copied_from_rescan')->default(0)->comment('1 => old result, 0 => new result');
			$table->text('tags', 65535)->nullable();
			$table->float('child_rank', 10, 0)->nullable();
			$table->bigInteger('child_id')->unsigned()->nullable();
			$table->boolean('meta_deleted')->default(0)->comment('0=>not deleted, 1=>deleted');
			$table->dateTime('extract_time')->nullable();
			$table->boolean('spidered')->nullable()->default(0);
			$table->boolean('is_parent')->default(0);
			$table->string('other_data')->nullable();
			$table->unique(['report_id','unique_content'], 'report_iddd');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sub_result');
	}

}
