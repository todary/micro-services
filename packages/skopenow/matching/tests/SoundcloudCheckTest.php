<?php

use Skopenow\Matching\Check\SoundcloudCheck;

class SoundcloudCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_soundcloud_check()
    {
        $url = 'https://soundcloud.com/liluzivert';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => 'Tom',
            'full_name' => 'liluzivert',
            'city' => 'cairo',
            'company' => 'Google',
            'email' => 'mohammed@gmail.com',
            'school' => 'MIT']
        );
        $check = new SoundcloudCheck($url, $person, $comb);
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
                'matchWith' => 'Ny',
            ],
        ];
        $this->assertEquals($expected['name'], $status['name']);
        $this->assertEquals($expected['location'], $status['location']);
    }

    /** @test */
    public function should_return_unmatched_data_from_soundcloud_check()
    {
        $url = 'https://angel.co/kevin-leung';
        $comb = ['id' => 10];
        $check = new SoundcloudCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
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
