<?php

use Skopenow\UrlInfo\{Profile, CURL};

class ProfileTest extends TestCase
{
    /** @test */
    public function is_profile_should_return_true_with_real_twitter_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://twitter.com/Ahmed_Abrass";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_twitter_status_url()
    {
        $profile = new Profile(new CURL);
        $url = "https://twitter.com/dafaa_news/status/901839280519225344";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_twitter_hashtag_url()
    {
        $profile = new Profile(new CURL);
        $url = "https://twitter.com/hashtag?q=hello";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }


    /** @test */
    public function is_profile_should_return_true_with_real_myspace_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://myspace.com/katelynryry";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_myspace_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://myspace.com/about/katelynryry";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_youtube_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.youtube.com/channel/UC-4KnPMmZzwAzW7SbVATUZQ";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_youtube_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.youtube.com/?about=channel/UC-4KnPMmZzwAzW7SbVATUZQ";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_google_plus_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://plus.google.com/115465732884397147672";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_not_real_google_plus_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://plus.google.com/about/mohammed";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }


    /** @test */
    public function is_profile_should_return_true_with_real_pinterest_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.pinterest.com/mohammedattya/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }


    /** @test */
    public function is_profile_should_return_false_with_non_real_pinterest_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.pinterest.com/?about=channel/UC-4KnPMmZzwAzW7SbVATUZQ";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_slideshare_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.slideshare.net/ryanholiday";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_slideshare_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.slideshare.net/about/channel";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_github_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.github.com/mohammeattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_github_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.github.com/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_angel_profile()
    {
        $content = ['body' => '{<div class="s-grid0-colMd20 s-grid--preMd2 subheader-inner-container">
            <div class="g-lockup-subheader"><div class="js-launchLargePhotoModal photo subheader-avatar">
            <img alt="Mohammed Attya" itemprop="image" class="js-avatar-img" src="https://d1qb2nb5cznatu.cloudfront.net/users/6848157-large?1503837772" />
            </div><div class="js-largePhotoModal mfp-hide u-hidden s-vgPad0_5">
            <div class="g-photo_container gigantic">
            <img alt="Mohammed Attya" itemprop="image" class="js-avatar-img" src="https://d1qb2nb5cznatu.cloudfront.net/users/6848157-large?1503837772" />
            </div>
            </div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $profile = new Profile($moch);
        $url = "https://angel.co/roofstock";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_angel_profile()
    {
        $content = ['body' => '{}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $profile = new Profile($moch);
        $url = "https://angel.co/roofstock?about=channel";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_twitch_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.twitch.tv/artyfakes";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_twitch_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.twitch.tv/artyfakes/laravel";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_ebay_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.ebay.com/usr/robalen";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_ebay_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.ebay.com/laravel";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_vine_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.vine.co/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_vine_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.vine.co/mohammedattya/profile";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_producthunt_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.producthunt.com/@mscccc";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_producthunt_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.producthunt.com/profile/mscccc";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_flickr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.flickr.com/photos/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_flickr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.flickr.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_flipboard_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.flipboard.com/@mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_flipboard_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.flipboard.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_steamcomunity_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://steamcommunity.com/id/123456789";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_steamcomunity_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://steamcommunity.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_foursquare_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.foursquare.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real2_foursquare_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://foursquare.com/user/444280681";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_foursquare_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.foursquare.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_okcupid_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://okcupid.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_okcupid_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.okcupid.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_vimeo_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.vimeo.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_vimeo_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.vimeo.com/people/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_etsy_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.etsy.com/people/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_etsy_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.etsy.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_soundcloud_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.soundcloud.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_soundcloud_profile()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.soundcloud.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_tumblr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://c-cassandra.tumblr.com/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_tumblr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://tumblr.com/c-cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_scribd_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://scribd.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_scribd_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://scribd.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_dailymotion_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.dailymotion.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_dailymotion_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.dailymotion.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_aboutme_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.about.me/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_aboutme_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.about.me/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_disqus_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.disqus.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_disqus_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.disqus.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_medium_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.medium.com/@ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_medium_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.medium.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_behance_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.behance.net/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_behance_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.behance.net/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_photobucket_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.photobucket.com/user/ccassandra/profile";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_photobucket_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.photobucket.com/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_kik_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.kik.com/u/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_kik_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.kik.com/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_imgur_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.imgur.com/user/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_imgur_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.imgur.com/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_bitly_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.bitly.com/u/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_bitly_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.bitly.com/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_instructables_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.instructables.com/member/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_instructables_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.instructables.com/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_gravatar_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.en.gravatar.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_gravatar_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.en.gravatar.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_keybase_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.keybase.io/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_keybase_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.keybase.io/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_kongregate_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.kongregate.com/accounts/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_kongregate_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.kongregate.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_stumbleupon_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.stumbleupon.com/stumbler/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_stumbleupon_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.stumbleupon.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_deviantart_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://ccassandra.deviantart.com/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_deviantart_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.deviantart.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_8tracks_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.8tracks.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_8tracks_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.8tracks.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_9gag_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.9gag.com/u/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_9gag_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.9gag.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_500px_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.500px.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_500px_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.500px.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_delicious_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.delicious.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_delicious_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.delicious.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_dribbble_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.dribbble.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_dribbble_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.dribbble.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_drupal_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.drupal.com/u/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_drupal_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.drupal.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_fiverr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.fiverr.com/u/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_fiverr_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.fiverr.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_lastfm_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.last.fm/user/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_lastfm_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.last.fm/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_linkedin_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.linkedin.com/in/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_linkedin_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.linkedin.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_metacafe_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.metacafe.com/channels/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_metacafe_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.metacafe.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_path_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.path.com/i/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_path_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.path.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_picasaweb_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.picasaweb.google.com/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_picasaweb_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.picasaweb.google.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_getgoogle_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.get.google.com/albumarchive/ccassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_getgoogle_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.get.google.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_picsart_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://ccassandra.picsart.com/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_picsart_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.picsart.com/profile/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_quora_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.quora.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_quora_profile_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.quora.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_quora_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.quora.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_reddit_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.reddit.com/user/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_reddit_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.reddit.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_squarespace_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://mohammedattya.squarespace.com/";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_squarespace_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.squarespace.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_tripadvisor_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.tripadvisor.com/member/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_tripadvisor_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.tripadvisor.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_tunein_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.tunein.com/user/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_tunein_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.tunein.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_wikipedia_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://en.wikipedia.org/wiki/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_wikipedia_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://en.wikipedia.org/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_wired_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://insights.wired.com/profile/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_wired_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://insights.wired.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_wordpress_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://profiles.wordpress.org/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_wordpress_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://profiles.wordpress.org/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_f6s_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.f6s.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_noreal_f6s_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.f6s.com/account/cassandra";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_instagram_profile()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.instagram.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_instagram_image()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.instagram.com/p/BYJWHboj5M6yw2lRDjKyDV1Fnl9RyxzJfGRT7M0";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_instagram_preprod()
    {
        $profile = new Profile(new CURL);
        $url = "https://preprod.instagram.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_instagram_image()
    {
        $profile = new Profile(new CURL);
        $url = "https://www.instagram.com/VIEW-INSTAGRAM/BYJWHboj5M6yw2lRDjKyDV1Fnl9RyxzJfGRT7M0";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_facebook_profile()
    {
        $htmlContent = '</div></div><div class="_5s61 _5cn0"><a class="_5b6s" href="https://m.facebook.com/profile/questions/view/1395730028_7_10201113132935878_0/?entry_point=msite_expanded_about_tab_prompt" aria-labelledby="u_0_2"></a></div></div></div></div><div class="mTimelineAboutEduwork/root"><div class="_56be _2xfb" id="work" data-sigil="profile-card"><div class="_55wo _55x2 _56bf"><header class="_56bq _52ja _52jh _59e9 _55wp _3knw __gy" data-sigil="profile-card-header"><div class="_55wr _4g33 _52we _5b6o"><div class="_4g34 _5b6q _5b6p"><div class="__gx">Work</div></div><div class="_4g34 _5b6r _5b6p"><div></div></div></div></header><div class="_55x2 _5ji7"><div class="_c3x _2lcw" id="add-2002"><div id="edit-add-2002" class="_c3r"></div><div class="async_elem _5t_s _52jh _18c9 _3_fs _c3u"><a class="_52jh _56bz _54k8 _5vv9" href="/profile/edit/infotab/section/forms/?';
        $profile = new Profile(new CURL);
        $profile->setHTMLContent(['body' => $htmlContent]);
        $url = "http://www.facebook.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_true_with_real_facebook_profile2()
    {
        $content = ['body' => '{"</div></div><div class="_5s61 _5cn0"><a class="_5b6s" href="https://m.facebook.com/profile/questions/view/1395730028_7_10201113132935878_0/?entry_point=msite_expanded_about_tab_prompt" aria-labelledby="u_0_2"></a></div></div></div></div><div class="mTimelineAboutEduwork/root"><div class="_56be _2xfb" id="work" data-sigil="profile-card"><div class="_55wo _55x2 _56bf"><header class="_56bq _52ja _52jh _59e9 _55wp _3knw __gy" data-sigil="profile-card-header"><div class="_55wr _4g33 _52we _5b6o"><div class="_4g34 _5b6q _5b6p"><div class="__gx">Work</div></div><div class="_4g34 _5b6r _5b6p"><div></div></div></div></header><div class="_55x2 _5ji7"><div class="_c3x _2lcw" id="add-2002"><div id="edit-add-2002" class="_c3r"></div><div class="async_elem _5t_s _52jh _18c9 _3_fs _c3u"><a class="_52jh _56bz _54k8 _5vv9" href="/profile/edit/infotab/section/forms/?"}
                    '];
        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $profile = new Profile($moch);
        $profile->setPersonID(123456);
        $profile->setCombinationID(123456);
        $url = "http://www.facebook.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(true, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_real_facebook_profile()
    {
        $content = ['body' => '{"bla bla bla"}
                    '];
        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $profile = new Profile($moch);
        $url = "http://www.facebook.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_facebook_post()
    {

        $profile = new Profile(new CURL);
        $url = "https://www.facebook.com/mohammed.attya25/posts/10210065501299492?pnref=story";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }

    /** @test */
    public function is_profile_should_return_false_with_non_listed_source()
    {
        $profile = new Profile(new CURL);
        $url = "http://www.youm7.com/mohammedattya";
        $actual = $profile->isProfile($url);
        $this->assertEquals(false, $actual);
    }
}
