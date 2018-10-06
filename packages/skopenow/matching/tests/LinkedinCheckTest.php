<?php

use Skopenow\Matching\Check\LinkedinCheck;

class LinkedinCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_linkedin_check()
    {
        $url = 'https://www.linkedin.com/in/johnmeulemans';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => 'Tom',
            'city' => 'cairo',
            'company' => 'Google',
            'email' => 'mohammed@gmail.com',
            'school' => 'MIT']
        );
        $check = new LinkedinCheck($url, $person, $comb);
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
                'matchWith' => '',
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
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['work'], $status['work']);
        $this->assertEquals($expected['username'], $status['username']);
    }

    /** @test */
    public function should_return_unmatched_data_from_linkedin_check()
    {
        $url = 'https://angel.co/kevin-leung';
        $comb = ['id' => 10];
        $check = new LinkedinCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
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
                'matchWith' => '',
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
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['work'], $status['work']);
        $this->assertEquals($expected['username'], $status['username']);
    }
}
