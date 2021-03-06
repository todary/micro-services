<?php

use Skopenow\Matching\Check\MyspaceCheck;

class MyspaceCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_myspace_check()
    {
        $url = 'https://www.myspace.com/rob';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => 'Rob',
            'last_name' => 'Douglas',
            'city' => 'cairo',
            'company' => 'Google',
            'email' => 'mohammed@gmail.com',
            'school' => 'MIT']
        );
        $check = new MyspaceCheck($url, $person, $comb);
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }

    /** @test */
    public function should_return_unmatched_data_from_myspace_check()
    {
        $url = 'https://angel.co/kevin-leung';
        $comb = ['id' => 10];
        $check = new MyspaceCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }
}
