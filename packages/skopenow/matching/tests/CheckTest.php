<?php

use Skopenow\Matching\Check\Check;
use Skopenow\Matching\Services\ReportService;

class CheckTest extends TestCase
{
    /** @test */
    public function check_test()
    {
        $info = [
            'name' => ['David Scott Laine', 'David Scott Laine'],
            'location' => ['Oyster Bay, NY'],
            'age' => 20,
            'work' => ['Facebook'],
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['00123456789'],
            'username' => 'starfire'
        ];
        $info2 = [
            'name' => ['David Scott Line', 'David Scott Laine'],
            'location' => ['Oyster Bay, NY'],
            'work' => ['Facebook'],
            'age' => 20,
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['+123456789'],
            'username' => 'starfire'
        ];
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
        config(['state.report_id' => 60019]);
        $check = new Check($report);
        $check->setMatchWith($info2);
        $check->setProfileInfo($info);
        $status = $check->check();

        $expected = [
            'name' => [
                'status' => true,
                'identities' => [
                    'fn'  => true,
                    'mn'  => true,
                    'ln'  => true,
                    'input_name' => false,
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
                    'pct' => false,
                    'st' => true,
                ],
                'matchWith' => 'Oyster Bay, Ny',
            ],
            'work' => [
                'status' => true,
                'identities' => [
                    'cm' => true,
                    'input_cm' => false,
                ],
                'matchWith' => 'Facebook',
            ],
            'school' => [
                'status' => true,
                'identities' => [
                    'sc' => true,
                    'input_sc' => false,
                ],
                'matchWith' => 'MIT',
            ],
            'age' => [
                'status' => true,
                'identities' => [
                    'age' => true,
                ],
                'matchWith' => 20,
            ],
            'email' => [
                'status' => true,
                'identities' => [
                    'em' => true,
                    'input_em' => false,
                ],
                'matchWith' => 'mohammed.attya25@gmail.com',
            ],
            'phone' => [
                'status' => true,
                'identities' => [
                    'ph' => true,
                    'input_ph' => false,
                ],
                'matchWith' => '123456789',
            ],
            'username' => [
                'status' => true,
                'identities' => [
                    'un' => true,
                    'input_un' => false,
                    'verified_un' => false,
                ],
                'matchWith' => 'starfire',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
        $this->assertEquals($expected['work'], $status['work']);
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['age'], $status['age']);
        $this->assertEquals($expected['email'], $status['email']);
        $this->assertEquals($expected['phone'], $status['phone']);
        $this->assertEquals($expected['username'], $status['username']);
    }

    /** @test */
    public function check_person_status_test()
    {
        $info = [
            'name' => ['Dovid Scotter Lines', 'Dovid Scotter Lines'],
            'location' => ['Oyster Bay, NY'],
            'age' => 20,
            'work' => ['Facebook'],
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['+123456789'],
            'username' => 'starfire'
        ];
        $info2 = [
            'name' => ['Dovid Scotter Lines', 'Dovid Scotter Lines'],
            'location' => ['New York, NY'],
            'work' => ['Facebook'],
            'age' => 20,
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['00123456789'],
            'username' => 'starfire'
        ];
        $person = [
            'id' => 60019,
            'city' => 'New York, NY',
            'state' => 'NY',
            'searched_names' => 'Dovid Scotter Lines',
            'company' => ['Facebook'],
            'name' => ['Dovid Scotter Lines', 'Dvid Scotte Lines'],
            'location' => ['Oyster Bay, NY'],
            'work' => ['Facebook'],
            'age' => 20,
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['00123456789'],
            'username' => 'starfire'
        ];
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
        config(['state.report_id' => 60019]);
        $check = new Check($report);
        $check->setMatchWith([]);
        $check->setProfileInfo($info);
        $status = $check->check();
        $expected = [
            'name' => [
                'status' => true,
                'identities' => [
                    'fn'  => true,
                    'mn'  => true,
                    'ln'  => true,
                    'input_name' => true,
                    'unq_name' => true,
                    'fzn' => false,
                ],
                'matchWith' => 'Dovid Scotter Lines',
            ],
            'location' => [
                'status' => true,
                'identities' => [
                    'exct-sm' => true,
                    'exct-bg' => false,
                    'input_loc' => true,
                    'pct' => false,
                    'st' => true,
                ],
                'matchWith' => 'Oyster Bay, Ny',
            ],
            'work' => [
                'status' => true,
                'identities' => [
                    'cm' => true,
                    'input_cm' => true,
                ],
                'matchWith' => 'Facebook',
            ],
            'school' => [
                'status' => true,
                'identities' => [
                    'sc' => true,
                    'input_sc' => true,
                ],
                'matchWith' => 'MIT',
            ],
            'age' => [
                'status' => true,
                'identities' => [
                    'age' => true,
                ],
                'matchWith' => 20,
            ],
            'email' => [
                'status' => true,
                'identities' => [
                    'em' => true,
                    'input_em' => true,
                ],
                'matchWith' => 'mohammed.attya25@gmail.com',
            ],
            'phone' => [
                'status' => true,
                'identities' => [
                    'ph' => true,
                    'input_ph' => true,
                ],
                'matchWith' => '123456789',
            ],
            'username' => [
                'status' => true,
                'identities' => [
                    'un' => true,
                    'input_un' => true,
                    'verified_un' => false,
                ],
                'matchWith' => 'starfire',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
        $this->assertEquals($expected['work'], $status['work']);
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['age'], $status['age']);
        $this->assertEquals($expected['email'], $status['email']);
        $this->assertEquals($expected['phone'], $status['phone']);
        $this->assertEquals($expected['username'], $status['username']);
    }

    /** @test */
    public function check_unmatched_person_status_test()
    {
        $info = [
            'name' => ['Mohammed Ali'],
            'location' => ['Oyster Bay, Ny'],
            'age' => 20,
            'work' => ['Queem Tech'],
            'school' => ['MIT'],
            'email' => ['mohammed.attya25@gmail.com'],
            'phone' => ['123456789'],
            'username' => 'starfire'
        ];
        $info2 = [
            'name' => ['Karim Ali'],
            'location' => ['Benha'],
            'age' => 30,
            'work' => ['Moseley'],
            'school' => ['Harvard'],
            'email' => ['karim@hotmail.com'],
            'phone' => ['09825312332'],
            'username' => 'karimAli'
        ];
        $person = [
            'id' => 60019,
            'city' => '',
            'state' => '',
            'searched_names' => '',
            'company' => [''],
            'name' => ['', ''],
            'location' => [''],
            'work' => [''],
            'age' => 20,
            'school' => [''],
            'email' => [''],
            'phone' => [''],
            'username' => ''
        ];
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
        config(['state.report_id' => 60019]);
        $check = new Check($report);
        $check->setMatchWith($info2);
        $check->setProfileInfo($info);
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
                'matchWith' => 'Benha',
            ],
            'work' => [
                'status' => true,
                'identities' => [
                    'cm' => false,
                    'input_cm' => false,
                ],
                'matchWith' => '',
            ],
            'school' => [
                'status' => true,
                'identities' => [
                    'sc' => false,
                    'input_sc' => false,
                ],
                'matchWith' => '',
            ],
            'age' => [
                'status' => true,
                'identities' => [
                    'age' => false,
                ],
                'matchWith' => 0,
            ],
            'email' => [
                'status' => true,
                'identities' => [
                    'em' => false,
                    'input_em' => false,
                ],
                'matchWith' => '',
            ],
            'phone' => [
                'status' => true,
                'identities' => [
                    'ph' => false,
                    'input_ph' => false,
                ],
                'matchWith' => '',
            ],
            'username' => [
                'status' => true,
                'identities' => [
                    'un' => false,
                    'input_un' => false,
                    'verified_un' => false,
                ],
                'matchWith' => '',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
        $this->assertEquals($expected['work'], $status['work']);
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['age'], $status['age']);
        $this->assertEquals($expected['email'], $status['email']);
        $this->assertEquals($expected['phone'], $status['phone']);
        $this->assertEquals($expected['username'], $status['username']);
    }
}
