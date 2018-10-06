<?php

use Skopenow\UrlInfo\Prune;

class PruneTest extends TestCase
{
    /** @test */
    public function prepare_content_should_prune_url_proberly()
    {
        $prune = new Prune;
        $url = "https://www.facebook.com/events/210473292817026/?";
        $actual = $prune->prepareContent($url);
        $expected = 'http://facebook.com/events/210473292817026';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function prepare_content_should_prune_url_proberly_from_pipl_url()
    {
        $prune = new Prune;
        $url = "https://pipl.com/search/?q=00201008403369&l=Cairo%2C+Egypt";
        $actual = $prune->prepareContent($url);
        $expected = 'http://pipl.com/search/?q=00201008403369&l=cairo%2c+egypt';
        $this->assertEquals($expected, $actual);
    }
}
