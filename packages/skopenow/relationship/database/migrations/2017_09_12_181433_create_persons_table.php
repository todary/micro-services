<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('first_name', 100);
            $table->string('middle_name', 100);
            $table->string('last_name', 100);
            $table->string('date_of_birth', 45)->nullable();
            $table->string('age', 50)->nullable();
            $table->text('address', 65535)->nullable();
            $table->text('street', 65535)->nullable();
            $table->text('city', 65535)->nullable();
            $table->text('state', 65535)->nullable();
            $table->string('country', 75);
            $table->integer('city_status')->default(0);
            $table->string('zip', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('company', 500)->nullable();
            $table->text('email', 65535)->nullable();
            $table->text('usernames', 65535);
            $table->text('added_usernames', 65535);
            $table->string('school', 100)->nullable();
            $table->integer('all_count')->nullable()->default(0);
            $table->integer('current')->nullable()->default(0);
            $table->integer('completed')->nullable()->default(0)->index('completed');
            $table->integer('combinations')->nullable()->default(0);
            $table->integer('current_combination')->nullable()->default(0);
            $table->integer('started')->default(0)->index('started');
            $table->integer('case_number')->nullable();
            $table->integer('user_id')->default(0)->index('user_id');
            $table->integer('corporate_id')->nullable()->index('corporate_id');
            $table->boolean('is_paid')->nullable();
            $table->integer('real_start_date')->default(0);
            $table->integer('insert_date')->default(0)->index('insert_date');
            $table->integer('end_date')->default(0);
            $table->integer('schedule_interval')->default(0);
            $table->integer('has_error')->default(0);
            $table->integer('schedule_now')->default(0);
            $table->integer('google_exc')->default(0);
            $table->text('search_combinations')->nullable();
            $table->string('func', 50)->nullable();
            $table->text('full_name', 65535)->nullable();
            $table->string('searched_names', 500)->nullable()->index('searched_names');
            $table->text('added_emails', 65535)->nullable();
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->integer('service_id')->nullable()->index('service_id');
            $table->float('cost', 10, 0)->nullable();
            $table->float('paid_amount', 10, 0)->default(0);
            $table->string('reference', 30)->nullable()->default('00000000')->index('reference');
            $table->boolean('is_api')->default(0)->index('is_api');
            $table->string('api_options', 2000)->nullable();
            $table->boolean('is_deleted')->default(0)->index('is_deleted');
            $table->boolean('is_hidden')->default(1)->index('is_hidden');
            $table->string('search_origin', 100)->nullable();
            $table->string('search_type', 100)->nullable();
            $table->string('reverse_source', 20)->nullable();
            $table->string('reverse_url', 350)->nullable();
            $table->string('user_ip', 20)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->char('version', 1)->default('S')->index('version')->comment('D=Development,B=Beta,R=Release Candidate,S=Stable');
            $table->timestamp('search_dateline')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('search_dateline');
            $table->dateTime('last_combination_run')->nullable()->index('last_combination_run');
            $table->boolean('profiles_in_results')->nullable()->default(0)->index('profiles_in_results');
            $table->boolean('is_charge')->default(0);
            $table->string('info_score', 200)->default('[]');
            $table->float('score', 10, 0)->default(0);
            $table->boolean('is_rescan')->default(0)->index('is_rescan');
            $table->boolean('rescan_enabled')->default(0);
            $table->integer('rescan_count')->default(0);
            $table->integer('rescan_allowed_count')->default(12);
            $table->text('rescan_settings', 65535)->nullable();
            $table->boolean('rescan_done')->default(0);
            $table->integer('rescan_type')->default(0)->comment('0=>Rescan Daily, 1=>Every Week, 2=>Every Month');
            $table->integer('rescan_from_id')->nullable();
            $table->date('rescan_expires')->nullable();
            $table->integer('number_of_changes')->default(0);
            $table->boolean('is_comb_proceeded')->default(0);
            $table->boolean('is_premium_search')->default(0)->index('is_premium_search');
            $table->integer('department_id')->nullable()->index('department_id');
            $table->integer('track_number')->default(0);
            $table->boolean('upgraded_to_premium')->default(0)->index('upgraded_to_premium');
            $table->integer('search_credit_count')->default(1);
            $table->integer('search_analyst_status')->default(0)->index('search_analyst_status');
            $table->float('on_complete_start_minute', 10, 0)->nullable();
            $table->string('on_complete_log_stream', 100)->nullable();
            $table->boolean('is_public')->nullable();
            $table->integer('sub_used_plan_id')->nullable();
            $table->integer('sub_used_price_id')->nullable();
            $table->integer('sub_used_addon_id')->nullable();
            $table->boolean('sub_is_extra_plan')->nullable();
            $table->boolean('sub_is_extra_addon')->nullable();
            $table->boolean('sub_is_premium')->nullable();
            $table->boolean('sub_is_extra_credit')->default(0);
            $table->float('sub_original_cost', 10, 0)->default(0);
            $table->boolean('is_void')->default(0);
            $table->boolean('show_void_label')->default(0);
            $table->string('void_reason', 100)->nullable();
            $table->string('filters')->nullable()->comment('JSON Field ');
            $table->text('init_data', 65535)->nullable();
            $table->string('view_settings', 100)->nullable();
            $table->index(['is_rescan', 'is_hidden', 'is_deleted'], 'is_rescan_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('persons');
    }
}
