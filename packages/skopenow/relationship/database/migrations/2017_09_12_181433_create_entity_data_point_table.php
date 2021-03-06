<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntityDataPointTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_data_point', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('report_id')->unsigned()->index('fk_entity_data_point_1_idx')->nullable();
            $table->bigInteger('entity_id')->unsigned()->index('fk_entity_data_point_2_idx')->nullable();
            $table->char('data_point_key', 32)->index('fk_entity_data_point_3_idx')->nullable();
            $table->unique(['report_id', 'data_point_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('entity_data_point');
    }

}
