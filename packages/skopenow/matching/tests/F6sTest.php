<?php

use Skopenow\Matching\Check\F6sCheck;
use Skopenow\Matching\Services\ReportService;

class F6sTest extends TestCase
{
    /** @test */
    public function should_return_matched_sm_exact_name_mn_data_from_f6s_check()
    {
        $info = ['name' => 'David Scott Laine', 'location' => 'Oyster Bay, NY'];
        $comb = [];
        $person = ['id' => 60019, 'city' => 'Oyster Bay, NY', 'state' => 'NY', 'searched_names' => 'David Scott Laine'];
        $url = '';
        $report = $this->getMockBuilder(ReportService::class)
                       ->disableOriginalConstructor()
                       ->getMock();
        $othernames = ['David Laine'];
        $report->method('getOtherNames')
               ->willReturn($othernames);
        $locations = ['Oyster Bay, NY'];
        $report->method('getAllPersonLocations')
               ->willReturn($locations);
        $names = ['David Laine'];
        $report->method('getAllPersonNames')
               ->willReturn($names);
        $report->method('loadAllLocations')
               ->willReturn($locations);
        $report->method('getReport')
               ->willReturn($person);

        $check = new F6sCheck($url, $info, $comb, $report);
        $status = $check->check();
        $expected = [
            'name' => [
                'status' => true,
                'identities' => [
                    'fn'  => true,
                    'mn'  => true,
                    'ln'  => true,
                    'input_name' => true,
                    'unq_name' => false,
                    'fzn' => false,
                ],
                'matchWith' => 'David Scott Laine',
            ],
            'location' => [
                'status' => true,
                'identities' => [
                    'exct-sm' => true,
                    'exct-bg' => false,
                    'input_loc' => false,
                    'pct' => true,
                    'st' => true,
                ],
                'matchWith' => 'Oyster Bay, Ny',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }

    /** @test */
    public function should_return_matched_data_from_f6s_check()
    {
        $info = ['name' => 'Rob Douglas', 'location' => 'New York, NY'];
        $comb = [];
        $person = ['id' => 60019, 'city' => 'Whittier', 'searched_names' => ''];
        $url = '';
        $report = $this->getMockBuilder(ReportService::class)
                       ->disableOriginalConstructor()
                       ->getMock();
        $othernames = ['Rob Douglas'];
        $report->method('getOtherNames')
               ->willReturn($othernames);
        $locations = ['New York, NY'];
        $report->method('getAllPersonLocations')
               ->willReturn($locations);
        $names = ['Rob Douglas'];
        $report->method('getAllPersonNames')
               ->willReturn($names);
        $report->method('loadAllLocations')
               ->willReturn($locations);
        $report->method('getReport')
               ->willReturn($person);

        $check = new F6sCheck($url, $info, $comb, $report);
        $status = $check->check();
        $expected = [
            'name' => [
                'status' => true,
                'identities' => [
                    'fn'  => true,
                    'mn'  => false,
                    'ln'  => true,
                    'input_name' => true,
                    'unq_name' => false,
                    'fzn' => false,
                ],
                'matchWith' => 'Rob Douglas',
            ],
            'location' => [
                'status' => true,
                'identities' => [
                    'exct-sm' => false,
                    'exct-bg' => true,
                    'input_loc' => false,
                    'pct' => true,
                    'st' => true,
                ],
                'matchWith' => 'Oyster Bay, Ny',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }

    /** @test */
    public function should_return_unmatched_data_from_f6s_check()
    {
        $info = ['name' => 'Rob Douglas', 'location' => 'Oyster Bay, NY'];
        $comb = [];
        $person = ['id' => 60019, 'city' => 'Cairo', 'state' => 'NY', 'searched_names' => ''];
        $url = '';
        $report = $this->getMockBuilder(ReportService::class)
                       ->getMock();
        $othernames = ['Tom Hanks'];
        $report->method('getOtherNames')
               ->willReturn($othernames);
        $locations = ['Cairo, Egypt'];
        $report->method('getAllPersonLocations')
               ->willReturn($locations);
        $names = ['Tom Hanks'];
        $report->method('getAllPersonNames')
               ->willReturn($names);
        $report->method('loadAllLocations')
               ->willReturn($locations);

        $check = new F6sCheck($url, $info, $person, $comb, $report);
        $status = $check->check();
        $expected = [
            'name' => [
                'status' => true,
                'identities' => [
                    'fn'  => false,
                    'mn'  => false,
                    'ln'  => false,
                    'input_name' => false,
                    'unq_name' => false,
                    'fzn' => false,
                ],
                'matchWith' => '',
            ],
            'location' => [
                'status' => true,
                'identities' => [
                    'exct-sm' => false,
                    'exct-bg' => false,
                    'input_loc' => false,
                    'pct' => false,
                    'st' => false,
                ],
                'matchWith' => 'Cairo, Egypt',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }
}
