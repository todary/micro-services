<?php

use Skopenow\UrlInfo\ProfileInfo\{
    DrupalProfileInfo,
    ScreennameaolProfileInfo,
    QuoraProfileInfo,
    FlipboardProfileInfo,
    InstructablesProfileInfo,
    GravatarProfileInfo,
    KongregateProfileInfo,
    MediumProfileInfo,
    TracksProfileInfo,
    WordpressProfileInfo,
    TuneinProfileInfo,
    KeybaseProfileInfo,
    WiredProfileInfo,
    PicsartProfileInfo,
    FiverrProfileInfo,
    LastfmProfileInfo,
    GagProfileInfo,
    YelpProfileInfo,
    PxProfileInfo,
    DailymotionProfileInfo,
    UstreamtvProfileInfo,
    PhotobucketProfileInfo,
    ProducthuntProfileInfo,
    BehanceProfileInfo,
    TwitchProfileInfo,
    PandoraProfileInfo,
    MetacafeProfileInfo,
    HubpagesProfileInfo,
    EtsyProfileInfo,
    DribbbleProfileInfo,
    SteamcommunityProfileInfo,
    EbayProfileInfo,
    AboutmeProfileInfo,
    TripadvisorProfileInfo,
    VimeoProfileInfo,
    SoundcloudProfileInfo,
    OkcupidProfileInfo,
    GetgoogleProfileInfo
};
use Skopenow\UrlInfo\UrlInfo\CURL;


class SocialSourcesProfileInfoTest extends \TestCase
{
    /** @test */
    public function should_return_drupal_profile_info()
    {
        $path = 'tests/profiles/drupal_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.drupal.org/u/mglaman";
        $profileInfo = new DrupalProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Matt Glaman mglaman', $info['name']);
        $this->assertEquals('https://www.drupal.org/files/styles/grid-2/public/user-pictures/picture-2416470-1485035212.jpg?itok=xS3cOVeI', $info['image']);
        $this->assertEquals('United States', $info['location']);
    }

    /** @test */
    public function should_return_drupal_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/drupal_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $url = "https://www.drupal.org/u/mglaman";
        $profileInfo = new DrupalProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Matt Glaman mglaman', $info['name']);
        $this->assertEquals('https://www.drupal.org/files/styles/grid-2/public/user-pictures/picture-2416470-1485035212.jpg?itok=xS3cOVeI', $info['image']);
        $this->assertEquals('United States', $info['location']);
    }

    /** @test */
    public function should_not_return_drupal_profile_info()
    {

        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.drupal.org/u/mglaman";
        $profileInfo = new DrupalProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['location']);
    }

    /** @test */
    public function should_return_screennameaol_profile_info()
    {
        $path = 'tests/profiles/screennameaol_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://profiles.aim.com/MissG3128";
        $profileInfo = new ScreennameaolProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('MissG3128', $info['name']);
        $this->assertEquals('http://api.oscar.aol.com/expressions/get?t=MissG3128&f=native&type=buddyIcon&defaultId=00052b0000316e', $info['image']);
    }

    /** @test */
    public function should_return_screennameaol_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/screennameaol_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "http://profiles.aim.com/MissG3128";
        $profileInfo = new ScreennameaolProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('MissG3128', $info['name']);
        $this->assertEquals('http://api.oscar.aol.com/expressions/get?t=MissG3128&f=native&type=buddyIcon&defaultId=00052b0000316e', $info['image']);
    }

    /** @test */
    public function should_not_return_screennameaol_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "http://profiles.aim.com/MissG3128";
        $profileInfo = new ScreennameaolProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_quora_profile_info()
    {
        $path = 'tests/profiles/quora_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.quora.com/profile/Henry-Walkley";
        $profileInfo = new QuoraProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Henry Walkley', $info['name']);
        $this->assertEquals('https://qph.ec.quoracdn.net/main-thumb-14126864-200-fNMvSA9bGUcxb2jxXA44BhHo6oaOCszc.jpeg', $info['image']);
    }


    /** @test */
    public function should_return_quora_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/quora_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.quora.com/profile/Henry-Walkley";
        $profileInfo = new QuoraProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Henry Walkley', $info['name']);
        $this->assertEquals('https://qph.ec.quoracdn.net/main-thumb-14126864-200-fNMvSA9bGUcxb2jxXA44BhHo6oaOCszc.jpeg', $info['image']);
    }

    /** @test */
    public function should_not_return_quora_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.quora.com/profile/Henry-Walkley";
        $profileInfo = new QuoraProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_flipboard_profile_info()
    {
        $path = 'tests/profiles/flipboard_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://flipboard.com/@mark";
        $profileInfo = new FlipboardProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Mark Plutowski', $info['name']);
        $this->assertEquals('https://cdn.flipboard.com/uploads/avatar/f83e81df0276b58666da3d01cd469dc0d6a33380.jpg', $info['image']);
    }

    /** @test */
    public function should_return_flipboard_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/flipboard_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://flipboard.com/@mark";
        $profileInfo = new FlipboardProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Mark Plutowski', $info['name']);
        $this->assertEquals('https://cdn.flipboard.com/uploads/avatar/f83e81df0276b58666da3d01cd469dc0d6a33380.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_flipboard_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://flipboard.com/@mark";
        $profileInfo = new FlipboardProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_instructables_profile_info()
    {
        $path = 'tests/profiles/instructables_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new InstructablesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://cdn.instructables.com/FN8/2CSV/H0AR6LOG/FN82CSVH0AR6LOG.SQUARE2.jpg', $info['image']);
        $this->assertEquals('Echo Park, CA', $info['location']);
    }

    /** @test */
    public function should_return_instructables_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/instructables_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new InstructablesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://cdn.instructables.com/FN8/2CSV/H0AR6LOG/FN82CSVH0AR6LOG.SQUARE2.jpg', $info['image']);
        $this->assertEquals('Echo Park, CA', $info['location']);
    }

    /** @test */
    public function should_not_return_instructables_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new InstructablesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['location']);
    }

    /** @test */
    public function should_return_gravatar_profile_info()
    {
        $path = 'tests/profiles/gravatar_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new GravatarProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://secure.gravatar.com/avatar/767fc9c115a1b989744c755db47feb60', $info['image']);
        $this->assertEquals('Matt', $info['name']);
    }

    /** @test */
    public function should_return_gravatar_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/gravatar_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new GravatarProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://secure.gravatar.com/avatar/767fc9c115a1b989744c755db47feb60', $info['image']);
        $this->assertEquals('Matt', $info['name']);
    }

    /** @test */
    public function should_not_return_gravatar_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://www.instructables.com/member/audreyobscura/";
        $profileInfo = new GravatarProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_kongregate_profile_info()
    {
        $path = 'tests/profiles/kongregate_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.kongregate.com/accounts/Ironhidegames";
        $profileInfo = new KongregateProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://cdn4.kongcdn.com/user_avatars/0393/5109/avatar_100x100.jpg?i10c=img.resize(width:140)', $info['image']);
        $this->assertEquals('Montevideo Uruguay', $info['location']);
    }

    /** @test */
    public function should_return_kongregate_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/kongregate_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.kongregate.com/accounts/Ironhidegames";
        $profileInfo = new KongregateProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://cdn4.kongcdn.com/user_avatars/0393/5109/avatar_100x100.jpg?i10c=img.resize(width:140)', $info['image']);
        $this->assertEquals('Montevideo Uruguay', $info['location']);
    }

    /** @test */
    public function should_not_return_kongregate_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "http://www.kongregate.com/accounts/Ironhidegames";
        $profileInfo = new KongregateProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['location']);
    }

    /** @test */
    public function should_return_medium_profile_info()
    {
        $path = 'tests/profiles/medium_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://medium.com/@jordanrosenfeld";
        $profileInfo = new MediumProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://cdn-images-1.medium.com/fit/c/100/100/1*4LzqmgECdpJfdMw7lvthHg.jpeg', $info['image']);
        $this->assertEquals('Jordan Rosenfeld', $info['name']);
    }

    /** @test */
    public function should_return_medium_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/medium_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://medium.com/@jordanrosenfeld";
        $profileInfo = new MediumProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://cdn-images-1.medium.com/fit/c/100/100/1*4LzqmgECdpJfdMw7lvthHg.jpeg', $info['image']);
        $this->assertEquals('Jordan Rosenfeld', $info['name']);
    }

    /** @test */
    public function should_not_return_medium_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://medium.com/@jordanrosenfeld";
        $profileInfo = new MediumProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_8tracks_profile_info()
    {
        $path = 'tests/profiles/tracks_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://8tracks.com/wildernessqueen";
        $profileInfo = new TracksProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://images.8tracks.com/avatar/i/010/027/499/tumblr_oo7hteFm791si0kwmo1_500-4384.jpg?rect=0,0,500,500&q=98&fm=jpg&fit=max&w=320&h=320', $info['image']);
        $this->assertEquals('Canada', $info['location']);
        $this->assertEquals('WildernessQueen', $info['name']);
    }

    /** @test */
    public function should_return_8tracks_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/tracks_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://8tracks.com/wildernessqueen";
        $profileInfo = new TracksProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://images.8tracks.com/avatar/i/010/027/499/tumblr_oo7hteFm791si0kwmo1_500-4384.jpg?rect=0,0,500,500&q=98&fm=jpg&fit=max&w=320&h=320', $info['image']);
        $this->assertEquals('Canada', $info['location']);
        $this->assertEquals('WildernessQueen', $info['name']);
    }

    /** @test */
    public function should_not_return_8tracks_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://8tracks.com/wildernessqueen";
        $profileInfo = new TracksProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_wordpress_profile_info()
    {
        $path = 'tests/profiles/wordpress_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://profiles.wordpress.org/andy";
        $profileInfo = new WordpressProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Andy Skelton', $info['name']);
        $this->assertEquals('http://www.gravatar.com/avatar/35756b05226763c9539679ccec26a1c0?s=150&#038;r=g&#038;d=mm', $info['image']);
        $this->assertEquals('Austin, Texas USA', $info['location']);
    }

    /** @test */
    public function should_return_wordpress_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/wordpress_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://profiles.wordpress.org/andy";
        $profileInfo = new WordpressProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Andy Skelton', $info['name']);
        $this->assertEquals('http://www.gravatar.com/avatar/35756b05226763c9539679ccec26a1c0?s=150&#038;r=g&#038;d=mm', $info['image']);
        $this->assertEquals('Austin, Texas USA', $info['location']);
    }

    /** @test */
    public function should_not_return_wordpress_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://profiles.wordpress.org/andy";
        $profileInfo = new WordpressProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['location']);
    }

    /** @test */
    public function should_return_tunein_profile_info()
    {
        $path = 'tests/profiles/tunein_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://tunein.com/radio/Capital-London-958-s16534/";
        $profileInfo = new TuneinProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Capital London', $info['name']);
        $this->assertEquals('https://cdn-radiotime-logos.tunein.com/s16534d.png', $info['image']);
    }

    /** @test */
    public function should_return_tunein_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/tunein_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://tunein.com/radio/Capital-London-958-s16534/";
        $profileInfo = new TuneinProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Capital London', $info['name']);
        $this->assertEquals('https://cdn-radiotime-logos.tunein.com/s16534d.png', $info['image']);
    }

    /** @test */
    public function should_not_return_tunein_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://tunein.com/radio/Capital-London-958-s16534/";
        $profileInfo = new TuneinProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_keybase_profile_info()
    {
        $path = 'tests/profiles/keybase_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://keybase.io/mikem";
        $profileInfo = new KeybaseProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Mike Maxim', $info['name']);
        $this->assertEquals('New York, NY', $info['location']);
        $this->assertEquals('https://s3.amazonaws.com/keybase_processed_uploads/ac6f09d4a6be5ed6f5a15e8aa83a6805_360_360_square_360.jpeg', $info['image']);
    }

    /** @test */
    public function should_return_keybase_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/keybase_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://keybase.io/mikem";
        $profileInfo = new KeybaseProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Mike Maxim', $info['name']);
        $this->assertEquals('New York, NY', $info['location']);
        $this->assertEquals('https://s3.amazonaws.com/keybase_processed_uploads/ac6f09d4a6be5ed6f5a15e8aa83a6805_360_360_square_360.jpeg', $info['image']);
    }

    /** @test */
    public function should_not_return_keybase_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn('');
        $url = "https://keybase.io/mikem";
        $profileInfo = new KeybaseProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_wired_profile_info()
    {
        $path = 'tests/profiles/wired_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.wired.com/author/geeks-guide-to-the-galaxy";
        $profileInfo = new WiredProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Geekx27s Guide to the Galaxy', $info['name']);
    }

    /** @test */
    public function should_return_wired_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/wired_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.wired.com/author/geeks-guide-to-the-galaxy";
        $profileInfo = new WiredProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Geekx27s Guide to the Galaxy', $info['name']);
    }

    /** @test */
    public function should_not_return_wired_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.wired.com/author/geeks-guide-to-the-galaxy";
        $profileInfo = new WiredProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_picsart_profile_info()
    {
        $path = 'tests/profiles/picsart_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://picsart.com/elikawatsonapola9";
        $profileInfo = new PicsartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Elika Watson', $info['name']);
        $this->assertEquals('https://cdn140.picsart.com/237448141080202.jpg?c120x120', $info['image']);
    }

    /** @test */
    public function should_return_picsart_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/picsart_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://picsart.com/elikawatsonapola9";
        $profileInfo = new PicsartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Elika Watson', $info['name']);
        $this->assertEquals('https://cdn140.picsart.com/237448141080202.jpg?c120x120', $info['image']);
    }

    /** @test */
    public function should_not_return_picsart_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://picsart.com/elikawatsonapola9";
        $profileInfo = new PicsartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_fiverr_profile_info()
    {
        $path = 'tests/profiles/fiverr_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.fiverr.com/maverickabhi?source=gig-cards";
        $profileInfo = new FiverrProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://fiverr-res.cloudinary.com/t_profile_small,q_auto,f_auto/profile/photos/3840831/original/P_20160828_073325_LL.jpg', $info['image']);
        $this->assertEquals('maverickabhi', $info['name']);
    }

    /** @test */
    public function should_return_fiverr_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/fiverr_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.fiverr.com/maverickabhi?source=gig-cards";
        $profileInfo = new FiverrProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://fiverr-res.cloudinary.com/t_profile_small,q_auto,f_auto/profile/photos/3840831/original/P_20160828_073325_LL.jpg', $info['image']);
        $this->assertEquals('maverickabhi', $info['name']);
    }

    /** @test */
    public function should_not_return_fiverr_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.fiverr.com/maverickabhi?source=gig-cards";
        $profileInfo = new FiverrProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_lastfm_profile_info()
    {
        $path = 'tests/profiles/lastfm_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.last.fm/user/tom";
        $profileInfo = new LastfmProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://lastfm-img2.akamaized.net/i/u/avatar170s/e798f4b64eba4ce3cc54b1b0679aa403.png', $info['image']);
        $this->assertEquals('tom', $info['name']);
    }

    /** @test */
    public function should_return_lastfm_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/lastfm_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.last.fm/user/tom";
        $profileInfo = new LastfmProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://lastfm-img2.akamaized.net/i/u/avatar170s/e798f4b64eba4ce3cc54b1b0679aa403.png', $info['image']);
        $this->assertEquals('tom', $info['name']);
    }

    /** @test */
    public function should_not_return_lastfm_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.last.fm/user/tom";
        $profileInfo = new LastfmProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_gag_profile_info()
    {
        $path = 'tests/profiles/gag_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://9gag.com/u/guitarrow";
        $profileInfo = new GagProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://accounts-cdn.9gag.com/media/avatar/24429657_100_12.jpg', $info['image']);
        $this->assertEquals('Dr Hazard', $info['name']);
    }

    /** @test */
    public function should_return_gag_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/gag_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://9gag.com/u/guitarrow";
        $profileInfo = new GagProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://accounts-cdn.9gag.com/media/avatar/24429657_100_12.jpg', $info['image']);
        $this->assertEquals('Dr Hazard', $info['name']);
    }

    /** @test */
    public function should_not_return_gag_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://9gag.com/u/guitarrow";
        $profileInfo = new GagProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_yelp_profile_info()
    {
        $path = 'tests/profiles/yelp_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.yelp.com/user_details?userid=PpdVTb0n5Irt2UDf3W3qrg";
        $profileInfo = new YelpProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://s3-media2.fl.yelpcdn.com/photo/auavougy7C89_jv8RZ9TCw/ls.jpg', $info['image']);
        $this->assertEquals('Laura F', $info['name']);
    }

    /** @test */
    public function should_return_yelp_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/yelp_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.yelp.com/user_details?userid=PpdVTb0n5Irt2UDf3W3qrg";
        $profileInfo = new YelpProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://s3-media2.fl.yelpcdn.com/photo/auavougy7C89_jv8RZ9TCw/ls.jpg', $info['image']);
        $this->assertEquals('Laura F', $info['name']);
    }

    /** @test */
    public function should_not_return_yelp_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.yelp.com/user_details?userid=PpdVTb0n5Irt2UDf3W3qrg";
        $profileInfo = new YelpProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_px_profile_info()
    {
        $path = 'tests/profiles/px_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://500px.com/nothernlight";
        $profileInfo = new PxProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Kamil Nureev', $info['name']);
        $this->assertEquals('https://pacdn.500px.org/2403507/c6120080f8d21fa50e05c129faa9a096ba911a79/1.jpg?4', $info['image']);
    }

    /** @test */
    public function should_return_px_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/px_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://500px.com/nothernlight";
        $profileInfo = new PxProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Kamil Nureev', $info['name']);
        $this->assertEquals('https://pacdn.500px.org/2403507/c6120080f8d21fa50e05c129faa9a096ba911a79/1.jpg?4', $info['image']);
    }

    /** @test */
    public function should_not_return_px_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://500px.com/nothernlight";
        $profileInfo = new PxProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_dailymotion_profile_info()
    {
        $path = 'tests/profiles/dailymotion_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.dailymotion.com/adam";
        $profileInfo = new DailymotionProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('http://s1.dmcdn.net/kVPFt/124x124-TUy.png', $info['image']);
        $this->assertEquals('ADAM', $info['name']);
    }

    /** @test */
    public function should_return_dailymotion_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/dailymotion_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.dailymotion.com/adam";
        $profileInfo = new DailymotionProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('http://s1.dmcdn.net/kVPFt/124x124-TUy.png', $info['image']);
        $this->assertEquals('ADAM', $info['name']);
    }

    /** @test */
    public function should_not_return_dailymotion_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.dailymotion.com/adam";
        $profileInfo = new DailymotionProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_ustreamtv_profile_info()
    {
        $path = 'tests/profiles/ustreamtv_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.ustream.tv/channel/jennifer";
        $profileInfo = new UstreamtvProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Jennifer', $info['name']);
        $this->assertEquals('http://static-cdn2.ustream.tv/i/channel/picture/5/0/8/9/5089/5089_jennifer-3-20-08,192x192,r:1.jpg', $info['image']);
    }

    /** @test */
    public function should_return_ustreamtv_profile_info_content()
    {
        $path = 'tests/profiles/ustreamtv_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.ustream.tv/channel/jennifer";
        $profileInfo = new UstreamtvProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Jennifer', $info['name']);
        $this->assertEquals('http://static-cdn2.ustream.tv/i/channel/picture/5/0/8/9/5089/5089_jennifer-3-20-08,192x192,r:1.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_ustreamtv_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.ustream.tv/channel/jennifer";
        $profileInfo = new UstreamtvProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_photobucket_profile_info()
    {
        $path = 'tests/profiles/photobucket_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://s1367.photobucket.com/user/Tom/library";
        $profileInfo = new PhotobucketProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Toms Library', $info['name']);
        $this->assertEquals('https://graph.facebook.com/100001710735471/picture?type=square', $info['image']);
    }

    /** @test */
    public function should_return_photobucket_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/photobucket_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://s1367.photobucket.com/user/Tom/library";
        $profileInfo = new PhotobucketProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Toms Library', $info['name']);
        $this->assertEquals('https://graph.facebook.com/100001710735471/picture?type=square', $info['image']);
    }

    /** @test */
    public function should_not_return_photobucket_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://s1367.photobucket.com/user/Tom/library";
        $profileInfo = new PhotobucketProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_producthunt_profile_info()
    {
        $path = 'tests/profiles/producthunt_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.producthunt.com/@chadwhitaker";
        $profileInfo = new ProducthuntProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Chad Whitaker', $info['name']);
        $this->assertEquals('https://ph-avatars.imgix.net/71/original?auto=format&auto=compress&codec=mozjpeg&cs=strip&w=140&h=140&fit=crop', $info['image']);
    }

    /** @test */
    public function should_return_producthunt_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/producthunt_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.producthunt.com/@chadwhitaker";
        $profileInfo = new ProducthuntProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Chad Whitaker', $info['name']);
        $this->assertEquals('https://ph-avatars.imgix.net/71/original?auto=format&auto=compress&codec=mozjpeg&cs=strip&w=140&h=140&fit=crop', $info['image']);
    }

    /** @test */
    public function should_not_return_producthunt_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.producthunt.com/@chadwhitaker";
        $profileInfo = new ProducthuntProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_behance_profile_info()
    {
        $path = 'tests/profiles/behance_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.behance.net/garthsykes";
        $profileInfo = new BehanceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Garth Sykes', $info['name']);
        $this->assertEquals('Los Angeles, CA, USA', $info['location']);
        $this->assertEquals('https://mir-s3-cdn-cf.behance.net/user/276/27e2df567379.596971c2a6f09.jpg', $info['image']);
    }

    /** @test */
    public function should_return_behance_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/behance_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.behance.net/garthsykes";
        $profileInfo = new BehanceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Garth Sykes', $info['name']);
        $this->assertEquals('Los Angeles, CA, USA', $info['location']);
        $this->assertEquals('https://mir-s3-cdn-cf.behance.net/user/276/27e2df567379.596971c2a6f09.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_behance_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.behance.net/garthsykes";
        $profileInfo = new BehanceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_twitch_profile_info()
    {
        $path = 'tests/profiles/twitch_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitch.tv/markiplier";
        $profileInfo = new TwitchProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://static-cdn.jtvnw.net/jtv_user_pictures/markiplier-profile_image-b35002cc6d4c2daa-300x300.png', $info['image']);
    }

    /** @test */
    public function should_return_twitch_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/twitch_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitch.tv/markiplier";
        $profileInfo = new TwitchProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://static-cdn.jtvnw.net/jtv_user_pictures/markiplier-profile_image-b35002cc6d4c2daa-300x300.png', $info['image']);
    }

    /** @test */
    public function should_not_return_twitch_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitch.tv/markiplier";
        $profileInfo = new TwitchProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_pandora_profile_info()
    {
        $path = 'tests/profiles/pandora_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.pandora.com/artist/ryan-stewart/ARp5Vnv4pwtPKc4";
        $profileInfo = new PandoraProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Ryan Stewart', $info['name']);
        $this->assertEquals('https://www.pandora.com/art/public/devicead/8/6/2/r/daar8161306r268_500W_500H.jpg', $info['image']);
    }

    /** @test */
    public function should_return_metacafe_profile_info()
    {
        $path = 'tests/profiles/metacafe_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.metacafe.com/channels/Kipkay";
        $profileInfo = new MetacafeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Kipkay', $info['name']);
        $this->assertEquals('http://cdn.metacafe.com/contents/avatars/3000000/3987000/3987419.jpg', $info['image']);
    }

    /** @test */
    public function should_return_metacafe_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/metacafe_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.metacafe.com/channels/Kipkay";
        $profileInfo = new MetacafeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Kipkay', $info['name']);
        $this->assertEquals('http://cdn.metacafe.com/contents/avatars/3000000/3987000/3987419.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_metacafe_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://www.metacafe.com/channels/Kipkay";
        $profileInfo = new MetacafeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_hubpages_profile_info()
    {
        $path = 'tests/profiles/hubpages_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://hubpages.com/@promisem";
        $profileInfo = new HubpagesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Linda Crampton', $info['name']);
        $this->assertEquals('British Columbia, Canada', $info['location']);
        $this->assertEquals('https://usercontent2.hubstatic.com/13430261_177.jpg', $info['image']);
    }

    /** @test */
    public function should_return_hubpages_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/hubpages_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://hubpages.com/@promisem";
        $profileInfo = new HubpagesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Linda Crampton', $info['name']);
        $this->assertEquals('British Columbia, Canada', $info['location']);
        $this->assertEquals('https://usercontent2.hubstatic.com/13430261_177.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_hubpages_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "http://hubpages.com/@promisem";
        $profileInfo = new HubpagesProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_etsy_profile_info()
    {
        $path = 'tests/profiles/etsy_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.etsy.com/people/IlGiardinoCreativo";
        $profileInfo = new EtsyProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Valentina Lanari', $info['name']);
        $this->assertEquals('Ancona, Italy', $info['location']);
        $this->assertEquals('https://img1.etsystatic.com/141/0/20496935/iusa_400x400.45668081_h86v.jpg', $info['image']);
    }

    /** @test */
    public function should_return_etsy_profile_info_content()
    {
        $path = 'tests/profiles/etsy_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.etsy.com/people/IlGiardinoCreativo";
        $profileInfo = new EtsyProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Valentina Lanari', $info['name']);
        $this->assertEquals('Ancona, Italy', $info['location']);
        $this->assertEquals('https://img1.etsystatic.com/141/0/20496935/iusa_400x400.45668081_h86v.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_etsy_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.etsy.com/people/IlGiardinoCreativo";
        $profileInfo = new EtsyProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_dribbble_profile_info()
    {
        $path = 'tests/profiles/dribbble_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new DribbbleProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Jord Riekwel', $info['name']);
        $this->assertEquals('Rotterdam, The Netherlands', $info['location']);
        $this->assertEquals('https://cdn.dribbble.com/users/1019/avatars/normal/1a9fb46c5a0d0b9238c11c5d8dd167f9.png?1490687363', $info['image']);
    }

    /** @test */
    public function should_return_dribbble_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/dribbble_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new DribbbleProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Jord Riekwel', $info['name']);
        $this->assertEquals('Rotterdam, The Netherlands', $info['location']);
        $this->assertEquals('https://cdn.dribbble.com/users/1019/avatars/normal/1a9fb46c5a0d0b9238c11c5d8dd167f9.png?1490687363', $info['image']);
    }

    /** @test */
    public function should_not_return_dribbble_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new DribbbleProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_steamcommunity_profile_info()
    {
        $path = 'tests/profiles/steamcommunity_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://steamcommunity.com/id/tom";
        $profileInfo = new SteamcommunityProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('RichT', $info['name']);
        $this->assertEquals('United Kingdom (Great Britain)', $info['location']);
        $this->assertEquals('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/27/273d65613cba0054a7911f70028d22d2c5d4294b_full.jpg', $info['image']);
    }

    /** @test */
    public function should_return_steamcommunity_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/steamcommunity_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://steamcommunity.com/id/tom";
        $profileInfo = new SteamcommunityProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('RichT', $info['name']);
        $this->assertEquals('United Kingdom (Great Britain)', $info['location']);
        $this->assertEquals('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/27/273d65613cba0054a7911f70028d22d2c5d4294b_full.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_steamcommunity_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://steamcommunity.com/id/tom";
        $profileInfo = new SteamcommunityProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_ebay_profile_info()
    {
        $path = 'tests/profiles/ebay_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new EbayProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('United States', $info['location']);
        $this->assertEquals('https://pics.ebaystatic.com/aw/pics/community/myWorld/imgBuddyBig33.gif', $info['image']);
    }

    /** @test */
    public function should_return_ebay_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/ebay_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new EbayProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('United States', $info['location']);
        $this->assertEquals('https://pics.ebaystatic.com/aw/pics/community/myWorld/imgBuddyBig33.gif', $info['image']);
    }

    /** @test */
    public function should_not_return_ebay_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://dribbble.com/Larkef";
        $profileInfo = new EbayProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_aboutme_profile_info()
    {
        $path = 'tests/profiles/aboutme_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://about.me/mohammed";
        $profileInfo = new AboutmeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Basrah, Basra Governorate, Iraq', $info['location']);
        $this->assertEquals('Mohammed Saad', $info['name']);
    }

    /** @test */
    public function should_return_aboutme_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/aboutme_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://about.me/mohammed";
        $profileInfo = new AboutmeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Basrah, Basra Governorate, Iraq', $info['location']);
        $this->assertEquals('Mohammed Saad', $info['name']);
    }

    /** @test */
    public function should_not_return_aboutme_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://about.me/mohammed";
        $profileInfo = new AboutmeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['name']);
    }

    /** @test */
    public function should_return_tripadvisor_profile_info()
    {
        $path = 'tests/profiles/tripadvisor_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.tripadvisor.com/members/vanjaap";
        $profileInfo = new TripadvisorProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('vanjaap', $info['name']);
        $this->assertEquals('London', $info['location']);
    }

    /** @test */
    public function should_return_tripadvisor_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/tripadvisor_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.tripadvisor.com/members/vanjaap";
        $profileInfo = new TripadvisorProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('vanjaap', $info['name']);
        $this->assertEquals('London', $info['location']);
    }

    /** @test */
    public function should_not_return_tripadvisor_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.tripadvisor.com/members/vanjaap";
        $profileInfo = new TripadvisorProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
    }

    /** @test */
    public function should_return_vimeo_profile_info()
    {
        $path = 'tests/profiles/vimeo_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vimeo.com/user28965802";
        $profileInfo = new VimeoProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Case Jernigan', $info['name']);
        $this->assertEquals('https://i.vimeocdn.com/portrait/13710182_640x640', $info['image']);
    }

    /** @test */
    public function should_return_vimeo_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/vimeo_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vimeo.com/user28965802";
        $profileInfo = new VimeoProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Case Jernigan', $info['name']);
        $this->assertEquals('https://i.vimeocdn.com/portrait/13710182_640x640', $info['image']);
    }

    /** @test */
    public function should_not_return_vimeo_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vimeo.com/user28965802";
        $profileInfo = new VimeoProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_soundcloud_profile_info()
    {
        $path = 'tests/profiles/soundcloud_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://soundcloud.com/liluzivert";
        $profileInfo = new SoundcloudProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('LIL UZI VERT', $info['name']);
        $this->assertEquals('United States , PHILADELPHIA', $info['location']);
        $this->assertEquals('https://i1.sndcdn.com/avatars-000331286184-lf5kta-large.jpg', $info['image']);
    }

    /** @test */
    public function should_return_soundcloud_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/soundcloud_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://soundcloud.com/liluzivert";
        $profileInfo = new SoundcloudProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('LIL UZI VERT', $info['name']);
        $this->assertEquals('United States , PHILADELPHIA', $info['location']);
        $this->assertEquals('https://i1.sndcdn.com/avatars-000331286184-lf5kta-large.jpg', $info['image']);
    }

    /** @test */
    public function should_not_return_soundcloud_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://soundcloud.com/liluzivert";
        $profileInfo = new SoundcloudProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_not_return_soundcloud_profile_info_2()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => 'hello world'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://soundcloud.com/liluzivert";
        $profileInfo = new SoundcloudProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['location']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_okcupid_profile_info()
    {
        $path = 'tests/profiles/okcupid_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new OkcupidProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('https://k2.okccdn.com/php/load_okc_image.php/images/225x225/225x225/0x2/750x752/2/11521860599839780864.jpeg?v=1', $info['image']);
    }

    /** @test */
    public function should_return_getgoogle_profile_info()
    {
        $path = 'tests/profiles/getgoogle_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new GetgoogleProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('Daniel', $info['name']);
        $this->assertEquals('https://lh3.googleusercontent.com/pRMywQpe02_FBL2Bl6GAnz-qWyoWFmX6c36viFdwl-pwc2vjBv7b9L5SQ8OALLcqG3obDXzV6VSJTcpfs0c=w540-h421', $info['image']);
    }

    /** @test */
    public function should_not_return_getgoogle_profile_info()
    {
        $content = ['body' => ''];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new GetgoogleProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('', $info['name']);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_return_okcupid_profile_info_htmlcontent()
    {
        $path = 'tests/profiles/okcupid_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new OkcupidProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals('https://k2.okccdn.com/php/load_okc_image.php/images/225x225/225x225/0x2/750x752/2/11521860599839780864.jpeg?v=1', $info['image']);
    }

    /** @test */
    public function should_not_return_okcupid_profile_info()
    {
        $content = ['body' => 'saddsafsa'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new OkcupidProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
    }

    /** @test */
    public function should_not_return_okcupid_profile_info2()
    {
        $content = ['body' => 'dasdsad', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.okcupid.com/profile/alyvamp";
        $profileInfo = new OkcupidProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('', $info['image']);
    }
}
