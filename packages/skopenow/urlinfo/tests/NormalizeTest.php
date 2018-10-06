<?php

use Skopenow\UrlInfo\URLNormalizer;

class NormalizeTest extends TestCase
{
    /** @test */
    public function normalize_url_should_return_normalized_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.facebook.com/people/Nicholas-Woodhams/12101952";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/profile.php?__sid=automation_sessions_facebook&id=12101952';
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_facebook_from_fb()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.fb.me/";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_normalize_fb_profile_php()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.facebook.com/profile.php?id=100009741667566";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/profile.php?__sid=automation_sessions_facebook&id=100009741667566';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_normalize_fb_comment()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.facebook.com/mohammed/posts/102143647417884?comment_id=102554635543089628";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/mohammed/posts/102143647417884';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_normalize_fb_photo_php()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.facebook.com/photo.php?fbid=1033451470025464&set=picfp.100000818611665.1015550085148936&type=3&theater";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/photo.php?fbid=1033451470025464';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_normalize_fb_permalink()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.facebook.com/permalink.php?story_fbid=1879262832089938&id=100000187193822";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.facebook.com/permalink.php?story_fbid=1879262832089938&id=100000187193822';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_piterest_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.pinterest.com/pin/348466089895466660/";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.pinterest.com/pin/348466089895466660/';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_github_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://github.com/kamranahmedse/developer-roadmap/issues/276";
        $actual = $normalize->normalize($url);
        $expected = 'https://github.com/kamranahmedse';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_picasa_url()
    {
        $normalize = new URLNormalizer;
        $url = "picasaweb.google.com/albumarchive/112285113559772702799/album/AF1QipOoACPyRDvDtbeFRjubWDp0Cjmb5sdIgGON88bn";
        $actual = $normalize->normalize($url);
        $expected = 'http://picasaweb.google.com/albumarchive/112285113559772702799/album/AF1QipOoACPyRDvDtbeFRjubWDp0Cjmb5sdIgGON88bn';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_linkedin_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://linkedin.com/profile/view?id=123456789";
        $actual = $normalize->normalize($url);
        $expected = 'http://linkedin.com/profile/view?id=123456789';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_linkedin_url_old()
    {
        $normalize = new URLNormalizer;
        $url = "http://www.linkedin.com/pub/heather-knies/a/810/b34";
        $actual = $normalize->normalize($url);
        $expected = 'http://www.linkedin.com/in/heather-knies-b34810a';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_slideshare_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.slideshare.net/AatifAwan/";
        $actual = $normalize->normalize($url);
        $expected = 'http://www.slideshare.net/AatifAwan/';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_youtube_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.youtube.com/watch?v=h3SjbFoGX20";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.youtube.com/watch?v=h3SjbFoGX20';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_youtube_watch_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.youtube.com/watch/JsW1GPUr6h8";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.youtube.com/watch?v=JsW1GPUr6h8';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_stumbleupon_url()
    {
        $normalize = new URLNormalizer;
        $url = "http://www.stumbleupon.com/su/7T2Loc";
        $actual = $normalize->normalize($url);
        $expected = 'http://www.stumbleupon.com/su/7T2Loc';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_instagram_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.instagram.com/mohammed/";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.instagram.com/mohammed';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_instagram_prepod_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://preprod.instagram.com/tanabailey09";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.instagram.com/tanabailey09';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_twitter_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.twitter.com/@mohammedattya";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.twitter.com/mohammedattya';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_quora_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.quora.com/profile/Mohammed-Attya";
        $actual = $normalize->normalize($url);
        $expected = 'http://quora.com/profile/Mohammed-Attya';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function normalize_url_should_return_normalized_unlisted_url()
    {
        $normalize = new URLNormalizer;
        $url = "https://www.youm7.com/#!/";
        $actual = $normalize->normalize($url);
        $expected = 'https://www.youm7.com/';
        $this->assertEquals($expected, $actual);
    }
}
