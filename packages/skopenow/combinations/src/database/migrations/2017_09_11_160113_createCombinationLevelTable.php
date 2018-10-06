<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combination_level', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comb_id')->unsigned();
            $table->tinyInteger('level_no')->default(1);
            $table->string('source', 50);
            $table->text('data');
            $table->float('start_minute')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('time_taken')->default(0);
            $table->tinyInteger('started')->default(0);
            $table->tinyInteger('is_completed')->default(0);
            $table->tinyInteger('trials')->default(0);
            $table->string('log_stream', 100)->nullable();
            $table->integer('exec_time')->default(0);
            $table->char('combinations_hash', 40)->nullable();
            $table->text('combs_fields')->nullable();
            $table->bigInteger('report_id');
            $table->integer('time');
            $table->tinyInteger('enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combination_level');
    }
}
