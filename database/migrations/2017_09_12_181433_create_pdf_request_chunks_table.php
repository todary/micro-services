<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePdfRequestChunksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pdf_request_chunks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->bigInteger('pdf_request_id')->index('pdf_request_id');
			$table->string('pdf_filename', 100);
			$table->string('chunk_html_url', 250);
			$table->string('pdf_url', 250)->nullable();
			$table->smallInteger('chunk_no')->index('chunk_no');
			$table->dateTime('start_date')->nullable();
			$table->dateTime('finish_date')->nullable();
			$table->boolean('is_started')->default(0)->index('is_started');
			$table->boolean('is_finished')->default(0)->index('is_finished');
			$table->boolean('has_error')->default(0)->index('has_error');
			$table->boolean('is_chunk_deleted')->default(0)->index('is_chunk_deleted');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pdf_request_chunks');
	}

}
