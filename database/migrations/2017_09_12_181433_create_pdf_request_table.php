<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePdfRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pdf_request', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('report_id')->index('report_id');
			$table->string('report_html_url', 300);
			$table->string('report_footer_url', 300)->nullable();
			$table->text('report_footer_html', 65535)->nullable();
			$table->string('pdf_filename', 100);
			$table->string('storage_type', 20);
			$table->string('pdf_url', 250)->nullable();
			$table->integer('results_count')->nullable()->index('results_count');
			$table->string('request_users', 50)->default('[]');
			$table->string('emails', 250)->nullable();
			$table->string('request_api', 50)->default('[]');
			$table->dateTime('request_date');
			$table->smallInteger('chunks_count')->default(1);
			$table->smallInteger('remaining_chunks')->default(0)->index('remaining_chunks');
			$table->dateTime('start_date')->nullable();
			$table->dateTime('finish_date')->nullable()->index('finish_date');
			$table->boolean('is_started')->default(0)->index('is_started');
			$table->boolean('is_finished')->default(0)->index('is_finished');
			$table->boolean('is_sent')->default(0)->index('is_sent');
			$table->boolean('has_error')->default(0)->index('has_error');
			$table->boolean('is_deleted')->default(0)->index('is_deleted');
			$table->boolean('is_premium')->default(0);
			$table->integer('regenerated_as')->nullable();
			$table->string('action', 150)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pdf_request');
	}

}
