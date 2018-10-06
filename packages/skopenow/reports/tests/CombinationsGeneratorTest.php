<?php

use Skopenow\Reports\CombinationGenerators\CombinationsGeneratorsFactory;
use Skopenow\Reports\EntryPoint;
use Illuminate\Support\Facades\Artisan;

class CombinationsGeneratorTest extends TestCase
{
    public function setUp()
    {
        $this->postData = [
            'name' => [
                'Tom kevin Doug',
            ],
            'first_name' => [
                'Tom kevin Doug',
            ],
            'last_name' => [
                'Tom kevin Doug',
            ],
            'location' => [
                'Oyster bay, New York',
            ],
            'address' => [
                '134  Cairo, Egypt',
            ],
            'age' => [
                50,
            ],
            'occupation' => [
                'teacher'
            ],
            'school' => [
                'MIT'
            ],
            'phone' => [
                '0122 325 123'
            ],
            'email' => [
                'mahmoud.magdy@queentechsolutions.net'
            ],
            'username' => [
                'maaa123'
            ],
        ];
        Artisan::call('migrate:refresh',['--path' => 'packages/skopenow/reports/database/migrations']);
        config(['state.user_id' => 4642]);
        $this->factory = new CombinationsGeneratorsFactory;
        // $reportId = $entry->generateReport($postData);
        // $entry->startSearch($reportId);
        // $encryptedID = encryptID($reportId);
        // echo "<a href='/lazy/r-$encryptedID' trage='_blank'>Lazy</a>";
    }

    /** @test */
    public function yellowpages_combinations_generator()
    {
        $source = 'yellowpages';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        $data = ['phone' => ['0100 840 33 69']];
        $reportId = (new EntryPoint)->generateReport($this->postData);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"ph":"0122 325 123"}');
    }

    /** @test */
    public function no_combinations_generated_for_yellowpages_no_phone()
    {
        $source = 'yellowpages';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        $data = ['email' => ['mohammed.attya25@gmail.com']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $this->assertEquals($level, null);
    }

    /** @test */
    public function whois_should_not_create_domain_comb_public_email()
    {
        $source = 'websites';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        \DB::table('email_blacklist')->insert(['domain' => 'gmail.com']);
        $data = ['email' => ['mohammed.attya25@gmail.com']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"em":"mohammed.attya25@gmail.com"}');
    }

    /** @test */
    public function whois_should_create_domain_comb_private_email()
    {
        $source = 'websites';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        \DB::table('email_blacklist')->insert(['domain' => 'gmail.com']);
        $data = ['email' => ['mohammed.attya25@queentechsolutions.net']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $level2 = DB::table('combination_level')->find(2);
        $this->assertEquals($level2->source, $source);
        $this->assertEquals($level2->data, '{"em":"mohammed.attya25@queentechsolutions.net"}');
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"dm":"queentechsolutions.net"}');
    }

    /** @test */
    public function whois_name_location_combinations_generator()
    {
        $source = 'websites';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        \DB::table('email_blacklist')->insert(['domain' => 'gmail.com']);
        $data = ['name' => ['Mohammed Attya'], 'location' => ['Oyster Bay, NY']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"fn":"mohammed","ln":"attya","ct":"Oyster Bay","st":"NY","name_status":"common","city_status":"bigCity"}');
    }

    /** @test */
    public function whois_name_location_email_domain_combinations_generator()
    {
        $source = 'websites';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        \DB::table('email_blacklist')->insert(['domain' => 'gmail.com']);
        $data = [
            'name' => ['Mohammed Attya'],
            'location' => ['Oyster Bay, NY'],
            'email' => ['mohammed.attya25@queentechsolutions.net']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $level2 = DB::table('combination_level')->find(2);
        $level3 = DB::table('combination_level')->find(3);
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"dm":"queentechsolutions.net"}');
        $this->assertEquals($level2->data, '{"fn":"mohammed","ln":"attya","ct":"Oyster Bay","st":"NY","name_status":"common","city_status":"bigCity"}');
        $this->assertEquals($level3->data, '{"em":"mohammed.attya25@queentechsolutions.net"}');
    }

    /** @test */
    public function whois_should_not_generate_name_location_comb_for_empty_location()
    {
        $source = 'websites';
        \DB::table('source')->insert(['name' => $source, 'main_source_id' => 2]);
        \DB::table('email_blacklist')->insert(['domain' => 'gmail.com']);
        $data = [
            'name' => ['Mohammed Attya'],
            'email' => ['mohammed.attya25@queentechsolutions.net']];
        $reportId = (new EntryPoint)->generateReport($data);
        config(['state.report_id' => $reportId]);
        $generator = $this->factory->make($source);
        $generator->generate($reportId);
        $level = DB::table('combination_level')->find(1);
        $level2 = DB::table('combination_level')->find(2);
        $this->assertEquals($level->source, $source);
        $this->assertEquals($level->data, '{"dm":"queentechsolutions.net"}');
        $this->assertEquals($level2->data, '{"em":"mohammed.attya25@queentechsolutions.net"}');
    }
}
