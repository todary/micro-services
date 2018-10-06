<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('role_id')->index('fk_user_role_idx');
			$table->integer('corporate_id')->nullable()->index('fk_user_corporate1_idx');
			$table->string('name', 100)->nullable()->index('name');
			$table->string('username', 45);
			$table->string('email', 150)->nullable()->unique('email');
			$table->string('password', 100)->nullable();
			$table->string('activkey', 128)->nullable();
			$table->boolean('superuser')->nullable()->default(0);
			$table->integer('status')->nullable();
			$table->integer('corporate_employee_status')->default(0);
			$table->string('phone', 45)->nullable();
			$table->text('info', 65535)->nullable();
			$table->string('company', 250)->index('company');
			$table->string('address', 150)->nullable();
			$table->boolean('is_auto_refill')->default(0);
			$table->integer('auto_refill_qty')->default(1);
			$table->integer('allowed_searches')->default(0);
			$table->integer('searches_used')->default(0);
			$table->integer('downloads_count')->default(0);
			$table->float('total_billed', 10, 0)->default(0);
			$table->float('total_owed', 10, 0)->default(0);
			$table->boolean('membership_status')->default(1)->comment('1=>active');
			$table->string('auth_customer_id', 45);
			$table->boolean('social_login')->default(0);
			$table->string('api_key', 45)->index('api_key');
			$table->string('api_key_id', 20)->nullable();
			$table->string('return_url');
			$table->boolean('free_trial_notified')->default(0);
			$table->dateTime('lastvisit_at')->nullable();
			$table->dateTime('lastsearch_at')->nullable();
			$table->dateTime('create_at')->nullable();
			$table->date('deactivated_at')->nullable();
			$table->integer('is_deleted')->default(0)->index('is_deleted');
			$table->boolean('direct_to_pdf')->default(0);
			$table->boolean('show_file_number')->nullable()->default(0);
			$table->integer('corporate_department_id')->nullable()->index('corporate_department_id');
			$table->boolean('enable_premium_search')->default(0);
			$table->integer('session_lifetime')->nullable()->comment('In hours');
			$table->boolean('tracking_email_notification')->default(0);
			$table->boolean('tracking_all_tracking')->default(0);
			$table->char('work_in_version', 1)->default('S');
			$table->boolean('show_meta')->default(0);
			$table->integer('api_plan_id');
			$table->boolean('api_enabled')->default(0)->comment('1 enabled');
			$table->string('api_push_url');
			$table->integer('api_usage')->default(0)->comment('to fetch the number of api usage by these user');
			$table->dateTime('api_limit_sent')->nullable()->comment('to determine when the last email had been sent');
			$table->boolean('force_logout')->default(0);
			$table->boolean('default_exact_search');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
