<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProgressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('progress', function(Blueprint $table)
		{
			$table->bigInteger('person_id')->unsigned()->primary();
			$table->integer('start')->default(0);
			$table->integer('time_taken')->default(0);
			$table->integer('time_remaining')->default(0);
			$table->integer('total_combinations')->default(0);
			$table->integer('completed_combinations')->default(0);
			$table->integer('relatives')->default(0);
			$table->integer('phones')->default(0);
			$table->integer('emails')->default(0);
			$table->integer('usernames')->default(0);
			$table->integer('addresses')->default(0);
			$table->integer('photos')->default(0);
			$table->integer('profiles')->default(0);
			$table->integer('websites')->default(0);
			$table->integer('results')->default(0);
			$table->integer('fullcontact')->default(0);
			$table->integer('whitepages')->default(0);
			$table->integer('google')->default(0);
			$table->integer('twitter')->default(0);
			$table->integer('youtube')->default(0);
			$table->integer('facebook_live_in')->default(0);
			$table->integer('facebook_hometown')->default(0);
			$table->integer('facebook_nearby')->default(0);
			$table->integer('pinterest')->default(0);
			$table->integer('spokeo')->default(0);
			$table->integer('lookup')->default(0);
			$table->integer('linkedin')->default(0);
			$table->integer('courtcasefinder')->default(0);
			$table->integer('intelius')->default(0);
			$table->integer('instantcheckmate')->default(0);
			$table->integer('beenverified')->default(0);
			$table->integer('tendigits')->default(0);
			$table->integer('locate411')->default(0);
			$table->integer('instagram')->default(0);
			$table->integer('pipl')->default(0);
			$table->integer('myspace')->default(0);
			$table->integer('mylife')->default(0);
			$table->integer('peekyou')->default(0);
			$table->integer('facebook_by_school')->default(0);
			$table->integer('facebook_by_company')->default(0);
			$table->integer('facebook_by_relatives')->default(0);
			$table->integer('fullcontact_total')->default(0);
			$table->integer('whitepages_total')->default(0);
			$table->integer('google_total')->default(0);
			$table->integer('googleplus')->default(0);
			$table->integer('googleplus_total')->default(0);
			$table->integer('twitter_total')->default(0);
			$table->integer('vine_total')->default(0);
			$table->integer('facebook_live_in_total')->default(0);
			$table->integer('facebook_hometown_total')->default(0);
			$table->integer('facebook_nearby_total')->default(0);
			$table->integer('pinterest_total')->default(0);
			$table->integer('spokeo_total')->default(0);
			$table->integer('lookup_total')->default(0);
			$table->integer('linkedin_total')->default(0);
			$table->integer('courtcasefinder_total')->default(0);
			$table->integer('intelius_total')->default(0);
			$table->integer('instantcheckmate_total')->default(0);
			$table->integer('beenverified_total')->default(0);
			$table->integer('tendigits_total')->default(0);
			$table->integer('locate411_total')->default(0);
			$table->integer('instagram_total')->default(0);
			$table->integer('pipl_total')->default(0);
			$table->integer('myspace_total')->default(0);
			$table->integer('mylife_total')->default(0);
			$table->integer('peekyou_total')->default(0);
			$table->integer('facebook_by_school_total')->default(0);
			$table->integer('facebook_by_company_total')->default(0);
			$table->integer('facebook_by_relatives_total')->default(0);
			$table->integer('case_usernames')->default(0);
			$table->integer('case_usernames_total')->default(0);
			$table->integer('yellowpages')->default(0);
			$table->integer('yellowpages_total')->default(0);
			$table->integer('whitepages_phone')->default(0);
			$table->integer('whitepages_phone_total')->default(0);
			$table->integer('whitepages_address')->default(0);
			$table->integer('whitepages_address_total')->default(0);
			$table->integer('twitterstatus')->default(0);
			$table->integer('twitterstatus_total')->default(0);
			$table->integer('youtube_total')->default(0);
			$table->integer('websites_total')->default(0);
			$table->integer('usernames_total')->default(0);
			$table->text('phones_data', 65535);
			$table->text('addresses_data', 16777215);
			$table->text('emails_data', 65535);
			$table->text('relatives_data', 16777215);
			$table->text('profiles_data', 65535);
			$table->text('assoc_profiles_data', 16777215)->nullable();
			$table->text('assoc_keys_data', 16777215)->nullable();
			$table->text('work_experiences_data', 65535)->nullable();
			$table->text('schools_data', 65535)->nullable();
			$table->text('websites_data', 65535)->nullable();
			$table->text('nicknames_data', 65535)->nullable();
			$table->text('names_data', 65535)->nullable();
			$table->text('age_data', 65535)->nullable();
			$table->text('added_usernames_data', 16777215)->nullable();
			$table->bigInteger('avatar_result_id')->default(0);
			$table->string('default_profile', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('progress');
	}

}
