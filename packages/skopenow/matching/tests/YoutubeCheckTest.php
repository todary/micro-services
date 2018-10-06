<?php

use Skopenow\Matching\Check\YoutubeCheck;

class YoutubeCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_youtube_check()
    {
        $url = 'https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw';
        $comb = ['id' => 10, 'combination_level' => 1, 'main_source' => 'google'];
        $person = new \ArrayIterator([
            'id' => 59954,
            'first_name' => 'Tom',
            'city' => 'cairo',
            'company' => 'Google',
            'email' => 'mohammed@gmail.com',
            'school' => 'MIT']
        );
        $check = new YoutubeCheck($url, $person, $comb);
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
    public function should_return_unmatched_data_from_youtube_check()
    {
        $url = 'https://angel.co/kevin-leung';
        $comb = ['id' => 10];
        $check = new YoutubeCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
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
