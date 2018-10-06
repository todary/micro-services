<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPdfRequestChunksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pdf_request_chunks', function(Blueprint $table)
		{
			$table->foreign('pdf_request_id', 'pdf_request_chunks_ibfk_1')->references('id')->on('pdf_request')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pdf_request_chunks', function(Blueprint $table)
		{
			$table->dropForeign('pdf_request_chunks_ibfk_1');
		});
	}

}
