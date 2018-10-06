<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combination', function (Blueprint $table) {
            $table->bigInteger('id', 1)->unsigned();
            $table->bigInteger('report_id')->unsigned();
            $table->integer('source_id');
            $table->tinyInteger('unique_name')->nullable();
            $table->tinyInteger('big_city')->nullable();
            $table->tinyInteger('is_generated')->nullable()->default(0);
            $table->string('additional', 350)->nullable();
            $table->string('username', 50)->nullable();
            $table->char('version', 1)->default('S');
            $table->text('extra_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combination');
    }
}
