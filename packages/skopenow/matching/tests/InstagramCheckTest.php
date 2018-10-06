<?php

use Skopenow\Matching\Check\InstagramCheck;

class InstagramCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_instagram_check()
    {
        $url = 'https://www.instagram.com/higgypop';
        $comb = ['id' => 10];
        $check = new InstagramCheck($url, new \ArrayIterator(['id' => 59954, 'first_name' => 'Tom', 'city' => 'cairo']), $comb);
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
    }

    /** @test */
    public function should_return_unmatched_data_from_instagram_check()
    {
        $url = 'https://angel.co/kevin-leung';
        $comb = ['id' => 10];
        $check = new InstagramCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
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
        ];
        $this->assertEquals($expected['name'], $status['name']);
    }
}
