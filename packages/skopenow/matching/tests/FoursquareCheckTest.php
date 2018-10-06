<?php

use Skopenow\Matching\Check\FoursquareCheck;

class FoursquareCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_foursquare_check()
    {
        $url = 'https://foursquare.com/y_bn_ahmed';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => 'Tom',
            'city' => 'cairo',
            'company' => 'Google',
            'email' => 'mohammed@gmail.com',
            'school' => 'MIT']
        );
        $check = new FoursquareCheck($url, $person, $comb);
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
                'matchWith' => 'Cairo',
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['work'], $status['work']);
    }/** @test */
    public function should_return_unmatched_data_from_foursquare_check()
    {
        $url = 'https://foursquare.com/y_bn_ahmed';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => '',
            'city' => '',
            'company' => '',
            'email' => '',
            'school' => '']
        );
        $check = new FoursquareCheck($url, $person, $comb);
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
        $this->assertEquals($expected['school'], $status['school']);
        $this->assertEquals($expected['work'], $status['work']);
    }
}
