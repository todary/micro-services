<?php

use Skopenow\UrlInfo\Source;

class SourceTest extends TestCase
{
    /** @test */
    public function should_return_twitter_as_source()
    {
        $url = "https://twitter.com/Ahmed_Abrass";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "twitter";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_twitter_as_main_source()
    {
        $url = "https://twitter.com/Ahmed_Abrass";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "twitter";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_facebook_as_source()
    {
        $url = "https://www.facebook.com/gaber.aljeko";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "facebook";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_facebook_as_main_source()
    {
        $url = "https://www.facebook.com/gaber.aljeko";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "facebook";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youtube_as_source()
    {
        $url = "https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "youtube";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youtube_as_main_source()
    {
        $url = "https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "youtube";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youtubeProfile_as_source_with_Profile_suffix()
    {
        $url = "https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw";
        $source = new Source;
        $source->setSourceSuffix('Profile');
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "youtubeProfile";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youtube_as_main_source_with_Profile_suffix()
    {
        $url = "https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw";
        $source = new Source;
        $source->setSourceSuffix('Profile');
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "youtube";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_pinterest_as_source()
    {
        $url = "https://www.pinterest.com/pin/417497827928164633/";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "pinterest";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youm7_as_source_setted_as_default_source()
    {
        $url = "https://www.youm7.com";
        $source = new Source;
        $source->setDefaultSource('youm7');
        $source->determineSource($url);
        $actual = $source->getSource();
        $expected = "youm7";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youm7_as_main_source_setted_as_default_main_source()
    {
        $url = "https://www.youm7.com";
        $source = new Source;
        $source->setDefaultMainSource('youm7');
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "youm7";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_pinterest_as_main_source()
    {
        $url = "https://www.pinterest.com/pin/417497827928164633/";
        $source = new Source;
        $source->determineSource($url);
        $actual = $source->getMainSource();
        $expected = "pinterest";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_real_source_name()
    {
        $sources = [
            "https://www.twitter.com/Mohammed.Attya" => 'twitter',
            "https://www.youtube.com/Mohammed.Attya" => 'youtube',
            "https://www.pinterest.com/Mohammed.Attya" => 'pinterest',
            "https://www.spokeo.com/Mohammed.Attya" => 'spokeospokeo',
            "https://www.10digits.us/Mohammed.Attya" => '10digits',
            "https://www.instagram.com/Mohammed.Attya" => 'instagram',
            "https://www.pipl.com/Mohammed.Attya" => 'pipl',
            "https://www.myspace.com/Mohammed.Attya" => 'myspace',
            "https://www.linkedin.com/Mohammed.Attya" => 'linkedin',
            "https://www.intelius.com/Mohammed.Attya" => 'intelius',
            "https://www.twitter.com/Mohammed.Attya" => 'twitter',
            "https://www.lookup.com/Mohammed.Attya" => 'lookup',
            "https://www.411locate.com/Mohammed.Attya" => '411locate',
            "https://www.youtube.com/Mohammed.Attya" => 'youtube',
            "https://www.facebook.com/Mohammed.Attya" => 'facebook',
            "https://www.peekyou.com/Mohammed.Attya" => 'peekyou',
            "https://www.courtcasefinder.com/Mohammed.Attya" => 'courtcasefinder',
            "https://www.plus.google.com" => 'googleplus',
            "https://www.flickr.com/Mohammed.Attya" => 'flickr',
            "https://www.picasaweb.google.com/Mohammed.Attya" => 'picasaweb',
            "https://www.photostream.com/Mohammed.Attya" => 'photostream',
            "https://www.meetup.com/Mohammed.Attya" => 'meetup',
            "https://www.radaris.com/Mohammed.Attya" => 'radaris',
            "https://www.peoplesmart.com/Mohammed.Attya" => 'peoplesmart',
            "https://www.peoplesmart.com/Mohammed.Attya" => 'peoplesmart',
            "https://www.411.com/Mohammed.Attya" => '411',
            "https://www.vimeo.com/Mohammed.Attya" => 'vimeo',
            "https://www.fastcompany.com/Mohammed.Attya" => 'fastcompany',
            "https://www.findthecompany.com/Mohammed.Attya" => 'findthecompany',
            "https://www.github.com/Mohammed.Attya" => 'github',
            "https://www.slideshare.net/Mohammed.Attya" => 'slideshare',
            "https://www.instructables.com/Mohammed.Attya" => 'instructables',
        ];
        $source = new Source;
        foreach ($sources as $url => $sourceName) {
            $source->determineSource($url);
            $actual = $source->getSource();
            $expected = $sourceName;
            $this->assertEquals($expected, $actual);
        }
    }

    /** @test */
    public function should_return_real_main_source_name()
    {
        $sources = [
            "https://www.twitter.com/Mohammed.Attya" => 'twitter',
            "https://www.youtube.com/Mohammed.Attya" => 'youtube',
            "https://www.pinterest.com/Mohammed.Attya" => 'pinterest',
            "https://www.spokeo.com/Mohammed.Attya" => 'spokeospokeo',
            "https://www.10digits.us/Mohammed.Attya" => '10digits',
            "https://www.instagram.com/Mohammed.Attya" => 'instagram',
            "https://www.pipl.com/Mohammed.Attya" => 'pipl',
            "https://www.myspace.com/Mohammed.Attya" => 'myspace',
            "https://www.linkedin.com/Mohammed.Attya" => 'linkedin',
            "https://www.intelius.com/Mohammed.Attya" => 'intelius',
            "https://www.twitter.com/Mohammed.Attya" => 'twitter',
            "https://www.lookup.com/Mohammed.Attya" => 'lookup',
            "https://www.411locate.com/Mohammed.Attya" => '411locate',
            "https://www.youtube.com/Mohammed.Attya" => 'youtube',
            "https://www.facebook.com/Mohammed.Attya" => 'facebook',
            "https://www.peekyou.com/Mohammed.Attya" => 'peekyou',
            "https://www.courtcasefinder.com/Mohammed.Attya" => 'courtcasefinder',
            "https://www.plus.google.com" => 'googleplus',
            "https://www.flickr.com/Mohammed.Attya" => 'flickr',
            "https://www.picasaweb.google.com/Mohammed.Attya" => 'picasaweb',
            "https://www.photostream.com/Mohammed.Attya" => 'photostream',
            "https://www.meetup.com/Mohammed.Attya" => 'meetup',
            "https://www.radaris.com/Mohammed.Attya" => 'radaris',
            "https://www.peoplesmart.com/Mohammed.Attya" => 'peoplesmart',
            "https://www.peoplesmart.com/Mohammed.Attya" => 'peoplesmart',
            "https://www.411.com/Mohammed.Attya" => '411',
            "https://www.vimeo.com/Mohammed.Attya" => 'vimeo',
            "https://www.fastcompany.com/Mohammed.Attya" => 'fastcompany',
            "https://www.findthecompany.com/Mohammed.Attya" => 'findthecompany',
            "https://www.github.com/Mohammed.Attya" => 'github',
            "https://www.slideshare.net/Mohammed.Attya" => 'slideshare',
            "https://www.instructables.com/Mohammed.Attya" => 'instructables',
        ];
        $source = new Source;
        foreach ($sources as $url => $sourceName) {
            $source->determineSource($url);
            $actual = $source->getMainSource();
            $expected = $sourceName;
            $this->assertEquals($expected, $actual);
        }
    }

    /** @test */
    public function should_return_real_source_name_from_social_sources()
    {
        $socialSources =
        [
            "angel" => "http://www.angel.com/Mohammed.Attya",
            "drupal" => "http://www.drupal.com/Mohammed.Attya",
            "pandora" => "http://www.pandora.com/Mohammed.Attya",
            "metacafe" => "http://www.metacafe.com/Mohammed.Attya",
            "twitpic" => "http://www.twitpic.com/Mohammed.Attya",
            "hubpages" => "http://www.hubpages.com/Mohammed.Attya",
            "github" => "http://www.github.com/Mohammed.Attya",
            "dribbble" => "http://www.dribbble.com/Mohammed.Attya",
            "etsy" => "http://www.etsy.com/Mohammed.Attya",
            "deviantart" => "http://www.deviantart.com/Mohammed.Attya",
            "youtube" => "http://www.youtube.com/Mohammed.Attya",
            "foursquare" => "http://www.foursquare.com/Mohammed.Attya",
            "myspace" => "http://www.myspace.com/Mohammed.Attya",
            "instagram" => "http://www.instagram.com/Mohammed.Attya",
            "flickr" => "http://www.flickr.com/Mohammed.Attya",
            "steamcommunity" => "http://www.steamcommunity.com/Mohammed.Attya",
            "ebay" => "http://www.ebay.com/Mohammed.Attya",
            "plancast" => "http://www.plancast.com/Mohammed.Attya",
            "about.me" => "http://www.about.me.com/Mohammed.Attya",
            "tripadvisor" => "http://www.tripadvisor.com/Mohammed.Attya",
            "vimeo" => "http://www.vimeo.com/Mohammed.Attya",
            "dailymotion" => "http://www.dailymotion.com/Mohammed.Attya",
            "soundcloud" => "http://www.soundcloud.com/Mohammed.Attya",
            "quora" => "http://www.quora.com/Mohammed.Attya",
            "lifestream.aol" => "http://www.lifestream.aol.com/Mohammed.Attya",
            "slideshare" => "http://www.slideshare.com/Mohammed.Attya",
            "twitch" => "http://www.twitch.com/Mohammed.Attya",
            "vine" => "http://www.vine.com/Mohammed.Attya",
            "medium" => "http://www.medium.com/Mohammed.Attya",
            "behance" => "http://www.behance.com/Mohammed.Attya",
            "photobucket" => "http://www.photobucket.com/Mohammed.Attya",
            "producthunt" => "http://www.producthunt.com/Mohammed.Attya",
            "kik.me" => "http://www.kik.me.com/Mohammed.Attya",
            "flipboard" => "http://www.flipboard.com/Mohammed.Attya",
            "bitly" => "http://www.bitly.com/Mohammed.Attya",
            "okcupid" => "http://www.okcupid.com/Mohammed.Attya",
            "instructables" => "http://www.instructables.com/Mohammed.Attya",
            "gravatar" => "http://www.gravatar.com/Mohammed.Attya",
            "keybase" => "http://www.keybase.com/Mohammed.Attya",
            "kongregate" => "http://www.kongregate.com/Mohammed.Attya",
            "stumbleupon" => "http://www.stumbleupon.com/Mohammed.Attya",
            "scribd" => "http://www.scribd.com/Mohammed.Attya",
            "8tracks" => "http://www.8tracks.com/Mohammed.Attya",
            "wordpress" => "http://www.wordpress.com/Mohammed.Attya",
            "wired" => "http://www.wired.com/Mohammed.Attya",
            "tunein" => "http://www.tunein.com/Mohammed.Attya",
            "picsart" => "http://www.picsart.com/Mohammed.Attya",
            "picasaweb" => "http://www.picasaweb.com/Mohammed.Attya",
            "get.google" => "http://www.get.google.com/Mohammed.Attya",
            "linkedin" => "http://www.linkedin.com/Mohammed.Attya",
            "last.fm" => "http://www.last.fm.com/Mohammed.Attya",
            "fiverr" => "http://www.fiverr.com/Mohammed.Attya",
            "500px" => "http://www.500px.com/Mohammed.Attya",
            "9gag" => "http://www.9gag.com/Mohammed.Attya",
            "yelp" => "http://www.yelp.com/Mohammed.Attya",
            "ustream.tv" => "http://www.ustream.tv.com/Mohammed.Attya",
        ];

        $source = new Source;
        foreach ($socialSources as $sourceName => $url) {
            $source->determineSource($url);
            $actual = $source->getSource();
            $expected = $sourceName;
            $this->assertEquals($expected, $actual);
        }
    }

    /** @test */
    public function should_return_search_results_from_google_source()
    {
        $url = "https://www.google.com";
        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Search Results', $siteTag);
    }

    /** @test */
    public function should_return_search_results_from_search_sources_list()
    {
        $urls = [
            'http://www.peekyou.com/Mohammed',
            'http://www.lookup.com/Mohammed',
            'http://www.google.com/Mohammed',
            'http://www.bing.com/Mohammed',
            'http://www.intelius.com/Mohammed',
            'http://www.pipl.com/Mohammed',
            'http://www.yellowpage.com/Mohammed',
            'http://www.10digits.us/Mohammed',
            'http://www.courtcasefinder.com/Mohammed',
            'http://www.411locate.com/Mohammed',
            'http://www.spokeo.com/Mohammed'
        ];

        $source = new Source;
        foreach ($urls as $url) {
            $siteTag = $source->getSiteTag($url);
            $this->assertEquals('Search Results', $siteTag);
        }
    }

    /** @test */
    public function should_return_Profile_from_social_sources_list()
    {
        $urls = [
            "http://www.plus.google.com/Mohammed",
            "http://www.twitter.com/Mohammed",
            "http://www.pinterest.com/Mohammed",
            "http://www.myspace.com/Mohammed",
            "http://www.linkedin.com/Mohammed",
            "http://www.instagram.com/Mohammed",
            "http://www.youtube.com/Mohammed",
        ];

        $source = new Source;
        foreach ($urls as $url) {
            $siteTag = $source->getSiteTag($url);
            $this->assertEquals('Profile', $siteTag);
        }
    }

    /** @test */
    public function should_return_Posts_from_facebook_post_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/posts/10159420110950314';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Posts', $siteTag);
    }

    /** @test */
    public function should_return_Messages_from_facebook_message_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/messages';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Messages', $siteTag);
    }

    /** @test */
    public function should_return_Pinterest_from_facebook_pinterest_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/app_pinterestapp';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Pinterest', $siteTag);
    }

    /** @test */
    public function should_return_Instagram_from_facebook_instagram_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/app_instapp';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Instagram', $siteTag);
    }

    /** @test */
    public function should_return_Foursquare_from_facebook_foursquare_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/app_playfoursquare';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Foursquare', $siteTag);
    }

    /** @test */
    public function should_return_Yelp_from_facebook_yelp_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/app_yelpyelp';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Yelp', $siteTag);
    }

    /** @test */
    public function should_return_Airbnb_from_facebook_airbnb_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/app_airbedandbreakfast';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Airbnb', $siteTag);
    }

    /** @test */
    public function should_return_Movies_from_facebook_movies_url()
    {
        $url = 'https://www.facebook.com/bashmohandesx/movies';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Movies', $siteTag);
    }

    /** @test */
    public function should_return_Profile_from_facebook()
    {
        $url = 'https://www.facebook.com/';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Profile', $siteTag);
    }

    /** @test */
    public function should_return_Profile_from_facebook_help_url()
    {
        $url = 'https://www.facebook.com/help';

        $source = new Source;
        $siteTag = $source->getSiteTag($url);
        $this->assertEquals('Profile', $siteTag);
    }
}
