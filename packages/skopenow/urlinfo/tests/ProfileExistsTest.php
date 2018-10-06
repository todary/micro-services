<?php

use Skopenow\UrlInfo\{Profile, CURL};

class ProfileExistsTest extends TestCase
{

    /** @test */
    public function profile_exists_should_return_true_with_real_twitter_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://twitter.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_8tracks_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200], 'body' => 'bla bla'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.8tracks.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_8tracks_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.8tracks.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_yelp_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.yelp.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_yelp_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.yelp.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_twitch_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200], 'body' => 'bla bla', '_id' => 10, 'name' => 'mohammed'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.twitch.tv/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_twitch_profile()
    {
        $htmlContent = ['header' => ['http_code' => 404], 'body' => 'bla bla'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.twitch.tv";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_vine_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.vine.co/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_vine_profile()
    {
        $htmlContent = ['header' => ['http_code' => 404]];
        $profile = new Profile(new CURL);
        $url = "https://www.vine.co";
        $profile->setHTMLContent($htmlContent);
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_aboutme_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.about.me/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_aboutme_profile2()
    {
        $htmlContent = ['header' => ['http_code' => 200, 'url' => 'https://www.about.me/mohammed']];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.about.me/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_aboutme_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.about.me";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_xanga_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.xanga.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_xanga_profile_pattern()
    {
        $htmlContent = ['header' => ['http_code' => 200]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.xanga.com/mohammed";
        $profile->setPattern('#pattern#i');
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_xanga_profile_pattern_body()
    {
        $htmlContent = ['header' => ['http_code' => 200], 'body' => 'hello world'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.xanga.com/mohammed";
        $profile->setPattern('#pattern#i');
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_xanga_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.xanga.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_delicious_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.delicious.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_delicious_username()
    {
        $htmlContent = ['header' => ['http_code' => 400, 'url' => '"https://www.delicious.com'], 'body' => ''];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.delicious.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_producthunt_profile()
    {
        $htmlContent = ['header' => ['http_code' => 200, 'url' => '"https://www.producthunt.com']];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.producthunt.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_producthunt_profile()
    {
        $htmlContent = ['header' => ['http_code' => 404, 'url' => '"https://www.producthunt.com/mohammed']];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.producthunt.com/about/me";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_true_with_real_9gag_profile2()
    {
        $htmlContent = ['header' => ['http_code' => 200, 'url' => 'https://www.9gag.com/mohammed'], 'body' => '{"pkg":{"username":"mohammed"}}'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.9gag.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real_9gag_profile()
    {
        $htmlContent = ['header' => ['http_code' => 404]];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.9gag.com";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function profile_exists_should_return_false_with_non_real2_9gag_profile2()
    {
        $htmlContent = ['header' => ['http_code' => 404], 'body' => '<title>9GAG - Go Fun Yourself</title>'];
        $profile = new Profile(new CURL);
        $profile->setHTMLContent($htmlContent);
        $url = "https://www.9gag.com/mohammed";
        $actual = $profile->profileExists($url);
        $this->assertEquals(false, $actual);
    }

}
