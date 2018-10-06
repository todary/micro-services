<?php

use Skopenow\Matching\Check\PicasaCheck;

class PicasaCheckTest extends TestCase
{
    /** @test */
    public function should_return_matched_data_from_picasa_check()
    {
        $url = 'https://picasaweb.google.com/112414462117529518114';
        $comb = ['id' => 10];
        $check = new PicasaCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
        $status = $check->check();
        $this->assertEquals(true, $status);
    }

    /** @test */
    public function should_return_unmatched_data_from_picasa_check()
    {
        $url = 'https://picasaweb.google.com/1124144621175295181';
        $comb = ['id' => 10];
        $check = new PicasaCheck($url, new \ArrayIterator(['id' => 59954]), $comb);
        $status = $check->check();
        $this->assertEquals(false, $status);
    }
}
