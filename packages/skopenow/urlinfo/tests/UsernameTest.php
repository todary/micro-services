<?php

use Skopenow\UrlInfo\Username;

class UsernameTest extends TestCase
{
    /** @test */
    public function should_return_mohammed_as_username_from_twitter_profile()
    {
        $url = "https://twitter.com/mohammed";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_as_username_from_youm7_profile()
    {
        $url = "https://en.youm7.com/mohammed";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_facebook_profile()
    {
        $url = "https://facebook.com/mohammed.attya25";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed.attya25";
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_mohammed_as_username_from_facebook_people_profile()
    {
        $url = "https://facebook.com/people/group/123456";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "123456";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_facebook_id_profile()
    {
        $url = "https://facebook.com/profile.php?id=1123245567";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "1123245567";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_linkedin_profile()
    {
        $url = "https://linkedin.com/in/mohammed";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_linkedin_profile_view()
    {
        $url = "https://www.linkedin.com/profile/view?id=123456789";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "123456789";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_foursquare_profile()
    {
        $url = "https://foursquare.com/mohammed";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_as_username_from_invalid_foursquare_profile()
    {
        $url = "https://foursquare.com/about/attya";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_foursquare_profile_user()
    {
        $url = "https://foursquare.com/user/123456789";
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "123456789";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_twitch_profile()
    {
        $url = 'http://www.twitch.tv/mohammed/profile';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_twitch_api_profile()
    {
        $url = 'http://www.api.twitch.tv/mohammed/profile';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_squarespace_profile()
    {
        $url = 'http://mohammed.squarespace.com';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_mohammed_as_username_from_wired_profile()
    {
        $url = 'http://www.insights.wired.com/profile/mohammed';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "mohammed";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_as_username_from_unvalid_wired_profile()
    {
        $url = 'http://www.insights.wired.com/mohammed';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_as_username_from_unvalid_twitter_profile()
    {
        $url = 'https://twitter.com/aezzarab25/status/901914056491229184';
        $username = new Username($url);
        $actual = $username->getUsername();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_mohammed_as_username_from_alist_of_urls()
    {
        $urls = [
            'http://plus.google.com/mohammed',
            'http://www.twitter.com/mohammed',
            'http://www.facebook.com/mohammed',
            'http://www.pinterest.com/mohammed',
            'http://www.instagram.com/mohammed',
            'http://www.myspace.com/mohammed',
            'http://www.youtube.com/user/mohammed',
            'http://www.youtube.com/channel/mohammed',
            'http://www.angel.co/mohammed',
            'http://www.slideshare.net/mohammed',
            'http://www.github.com/mohammed',
            'http://www.ebay.com/usr/mohammed',
            'http://www.vine.co/u/mohammed',
            'http://www.flickr.com/photos/mohammed',
            'http://www.pandora.com/profile/mohammed',
            'http://www.rdio.com/pepole/mohammed',
            'http://www.producthunt.com/@mohammed',
            'http://www.steamcommunity.com/id/mohammed',
            'http://www.flipboard.com/@mohammed',
            'http://www.okcupid.com/profile/mohammed',
            'http://www.vimeo.com/mohammed',
            'http://www.etsy.com/people/mohammed',
            'http://www.soundcloud.com/mohammed',
            'http://mohammed.tumblr.com',
            'http://www.scribd.com/mohammed',
            'http://www.dailymotion.com/mohammed',
            'http://www.about.me/mohammed',
            'http://www.disqus.com/mohammed',
            'http://www.medium.com/@mohammed',
            'http://www.behance.net/mohammed',
            'http://www.photobucket.com/user/mohammed/profile',
            'http://www.kik.com/u/mohammed',
            'http://www.imgur.com/user/mohammed',
            'http://www.bitly.com/u/mohammed',
            'http://www.instructables.com/member/mohammed',
            'http://www.en.gravatar.com/mohammed',
            'http://www.keybase.io/mohammed',
            'http://www.kongregate.com/accounts/mohammed',
            'http://www.stumbleupon.com/stumbler/mohammed',
            'http://mohammed.deviantart.com/',
            'http://www.8tracks.com/mohammed',
            'http://www.9gag.com/u/mohammed',
            'http://www.500px.com/mohammed',
            'http://www.delicious.com/mohammed',
            'http://www.dribbble.com/mohammed',
            'http://www.drupal.com/u/mohammed',
            'http://www.fiverr.com/mohammed',
            'http://www.last.fm/user/mohammed',
            'http://www.linkedin.com/in/mohammed',
            'http://www.metacafe.com/channels/mohammed',
            'http://www.path.com/i/mohammed',
            'http://mohammed.picsart.com',
            'http://www.quora.com/profile/mohammed',
            'http://www.reddit.com/user/mohammed',
            'http://www.tripadvisor.com/member/mohammed',
            'http://www.tunein.com/user/mohammed',
            'http://www.wikipedia.org/wiki/mohammed',
            'http://www.profiles.wordpress.org/mohammed',
            'http://www.get.google.com/albumarchive/mohammed',
            'http://www.picasaweb.google.com/mohammed',
            'f6s.com/mohammed'
        ];
        foreach ($urls as $url) {
            $username = new Username($url);
            $actual = $username->getUsername();
            $expected = "mohammed";
            $this->assertEquals($expected, $actual);
        }
    }
}
