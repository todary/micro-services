<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserBannedDomainsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_banned_domains', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('url', 200);
            $table->string('source', 200)->nullable();
            $table->dateTime('dateline');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_banned_domains');
    }

}
