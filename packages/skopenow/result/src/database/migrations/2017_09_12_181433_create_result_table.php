<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned()->primary();
            $table->bigInteger('report_id')->unsigned()->index('fk_result_1_idx');
            $table->integer('source_id')->index('fk_result_2_idx');
            $table->string('source', 50);
            $table->bigInteger('main_combination_id')->unsigned()->index('fk_result_3_idx');
            $table->string('url', 700);
            $table->string('type', 100);
            $table->string('raw_type', 15);
            $table->string('unique_content', 350)->nullable();
            $table->string('alternative_unique_content', 350)->nullable();
            $table->text('html', 65535)->nullable();
            $table->integer('date')->default(0);
            $table->string('identifiers', 1000)->nullable();
            $table->string('additional_data', 350)->nullable();
            $table->integer('has_siblings')->default(0);
            $table->string('username', 50)->nullable();
            $table->boolean('combination_level')->default(1);
            $table->float('comb_rank', 10, 0)->nullable()->default(0);
            $table->smallInteger('results_page_type_id')->nullable();
            $table->boolean('does_match_name')->nullable();
            $table->boolean('does_match_location')->nullable();
            $table->boolean('does_match_friendslist')->nullable();
            $table->integer('flags')->default(0);
            $table->integer('matching_flags')->default(0);
            $table->integer('input_flags')->default(0);
            $table->integer('extra_flags')->default(0);
            $table->boolean('info_extracted')->nullable();
            $table->boolean('display_level')->nullable()->default(0);
            $table->boolean('is_deleted')->nullable()->default(0);
            $table->boolean('invisible')->default(0)->comment('to make the result hidden until the search ended  ');
            $table->boolean('deletion_type')->nullable();
            $table->boolean('is_manual')->nullable()->default(0);
            $table->boolean('is_relative')->nullable()->default(0);
            $table->boolean('name_match')->default(0);
            $table->float('distance', 10, 0)->nullable();
            $table->integer('exact_filter')->default(0);
            $table->string('first_name', 50)->nullable();
            $table->boolean('is_profile')->nullable()->default(0);
            $table->boolean('copied_from_rescan')->default(0)->comment('1 => old result, 0 => new result');
            $table->string('profile_image', 300)->nullable();
            $table->string('profile_name', 50)->nullable();
            $table->string('profile_username', 50)->nullable();
            $table->text('tags', 65535)->nullable();
            $table->boolean('spidered')->nullable()->default(0);
            $table->string('other_data')->nullable();
            $table->string('custom_source_name', 50)->nullable();
            $table->string('score_identity', 150)->default('[]');
            $table->float('score_source', 10, 0)->default(0);
            $table->float('score_source_type', 10, 0)->default(0);
            $table->float('score_result_count', 10, 0)->default(0);
            $table->float('score', 10, 0)->default(0);
            $table->float('rank', 10, 0)->default(0);
            $table->boolean('meta_deleted')->default(0)->comment('0=>not deleted, 1=>deleted');
            $table->dateTime('extract_time')->nullable();
            $table->integer('account')->nullable();
            $table->unique(['report_id','url'], 'url_UNIQUE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('result');
    }

}
