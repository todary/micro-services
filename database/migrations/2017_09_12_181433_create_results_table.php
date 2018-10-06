<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('results', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('person_id')->unsigned()->index('person_id_2');
			$table->bigInteger('combination_id')->unsigned()->nullable()->index('combination_id');
			$table->bigInteger('related_to')->unsigned()->default(0)->index('related_to');
			$table->string('main_source', 50)->index('main_source');
			$table->string('source', 50)->index('source_id');
			$table->string('type', 100);
			$table->string('raw_type', 15)->index('raw_type');
			$table->string('content', 700);
			$table->string('unique_content', 350)->index('unique_content');
			$table->string('alternative_unique_content', 350)->nullable()->index('alt_unique_content');
			$table->integer('profile')->default(0);
			$table->text('html', 65535)->nullable();
			$table->integer('date')->default(0);
			$table->string('identifiers', 1000)->nullable();
			$table->string('additional_data', 350)->nullable();
			$table->integer('has_siblings')->default(0)->index('has_siblings');
			$table->integer('first_results')->default(0)->index('first_results');
			$table->string('username', 50)->nullable();
			$table->boolean('combination_level')->default(1);
			$table->float('comb_rank', 10, 0)->nullable()->default(0);
			$table->smallInteger('results_page_type_id')->nullable()->index('results_page_type_id');
			$table->boolean('does_match_name')->nullable();
			$table->boolean('does_match_location')->nullable();
			$table->boolean('does_match_friendslist')->nullable();
			$table->integer('flags')->default(0);
			$table->boolean('info_extracted')->nullable();
			$table->boolean('display_level')->nullable()->default(0)->index('display_level');
			$table->boolean('is_deleted')->nullable()->default(0)->index('is_deleted');
			$table->boolean('invisible')->default(0)->index('invisible')->comment('to make the result hidden until the search ended  ');
			$table->boolean('deletion_type')->nullable()->index('deletion_type');
			$table->boolean('is_manual')->nullable()->default(0);
			$table->boolean('is_relative')->nullable()->default(0)->index('is_relative');
			$table->boolean('name_match')->default(0)->index('name_match');
			$table->float('distance', 10, 0)->nullable()->index('distance');
			$table->integer('exact_filter')->default(0)->index('exact_filter');
			$table->string('first_name', 50)->nullable();
			$table->boolean('has_profile_data')->nullable()->default(0)->index('has_profile_data');
			$table->boolean('copied_from_rescan')->default(0)->comment('1 => old result, 0 => new result');
			$table->string('profile_image', 300)->nullable();
			$table->string('profile_name', 50)->nullable();
			$table->string('profile_username', 50)->nullable();
			$table->text('tags', 65535)->nullable();
			$table->boolean('spidered')->nullable()->default(0);
			$table->string('other_data')->nullable();
			$table->float('child_rank', 10, 0)->nullable();
			$table->bigInteger('child_id')->unsigned()->nullable();
			$table->string('custom_source_name', 50)->nullable();
			$table->string('score_identity', 150)->default('[]');
			$table->float('score_source', 10, 0)->default(0);
			$table->float('score_source_type', 10, 0)->default(0);
			$table->float('score_result_count', 10, 0)->default(0);
			$table->float('score', 10, 0)->default(0)->index('score');
			$table->float('rank', 10, 0)->default(0)->index('rank');
			$table->boolean('meta_deleted')->default(0)->index('meta_deleted')->comment('0=>not deleted, 1=>deleted');
			$table->dateTime('extract_time')->nullable();
			$table->integer('account')->nullable();
			$table->primary(['id','person_id']);
			$table->unique(['person_id','unique_content'], 'person_id_3');
			$table->index(['person_id','source'], 'person_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('results');
	}

}
