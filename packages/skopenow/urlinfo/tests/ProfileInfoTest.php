<?php

use Skopenow\UrlInfo\ProfileInfo\{
    PinterestProfileInfo,
    TwitterProfileInfo,
    FacebookProfileInfo,
    F6sProfileInfo,
    AngelProfileInfo,
    MyspaceProfileInfo,
    LinkedinProfileInfo,
    InstagramProfileInfo,
    DeviantartProfileInfo,
    GithubProfileInfo,
    WebstaProfileInfo,
    SlideshareProfileInfo,
    MeetupProfileInfo,
    FoursquareProfileInfo,
    FlickrProfileInfo,
    VineProfileInfo,
    YoutubeProfileInfo,
    GooglePlusProfileInfo
};
use Skopenow\UrlInfo\UrlInfo\CURL;

class ProfileInfoTest extends \TestCase
{
    /** @test */
    public function should_return_pinterest_profile_info()
    {
        $path = 'tests/profiles/pinterest_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://pinterest.com/blabla";
        $profileInfo = new PinterestProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Jenn Low", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['twitter']);
        $this->assertEquals("Founder of Wanderlust + Co // jewels for dreamers x lovers // a glimpse into the W+Co world... live beautifully x", $info['bio']);
        $this->assertEquals('https://s-media-cache-ak0.pinimg.com/avatars/jennifer_1405690691_140.jpg', $info['image']);
    }

    /** @test */
    public function should_return_twitter_profile_info()
    {
        $path = 'tests/profiles/twitter_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitter.com/blabla";
        $profileInfo = new TwitterProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Justin", $info['name']);
        $this->assertEquals("Las Vegas, NV", $info['location']);
        $this->assertEquals("https://pbs.twimg.com/profile_images/900657605697093632/5jbxJwyP_400x400.jpg", $info['image']);
        $this->assertEquals("https://3lau.com", $info['link']);
        $this->assertEquals("the sky was quiet as we flooded its veins Snapchat: Justin3lau", $info['bio']);
    }

    /** @test */
    public function should_return_facebook_profile_info()
    {
        $path = 'tests/profiles/facebook_profile_about_page.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://m.facebook.com/blabla";
        $profileInfo = new FacebookProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        dd($info['location']);
        $this->assertEquals("Jennifer Chen Taylor", $info['name']);
        $this->assertEquals([], $info['location']);
        $this->assertEquals("https://fb-s-c-a.akamaihd.net/h-ak-fbx/v/t1.0-1/cp0/e15/q65/p48x48/13510840_10154299187657838_4218160330552521709_n.png.jpg?efg=eyJpIjoidCJ9&oh=8d25433087e41f095c88cd1fc6f76908&oe=5A4D916B&__gda__=1511967540_ac7bf6d17f8b23098c605e2f2da14909", $info['image']);
        $experience = [[
            "image" => "https://fb-s-c-a.akamaihd.net/h-ak-fbx/v/t1.0-1/cp0/e15/q65/p48x48/13510840_10154299187657838_4218160330552521709_n.png.jpg?efg=eyJpIjoidCJ9&oh=8d25433087e41f095c88cd1fc6f76908&oe=5A4D916B&__gda__=1511967540_ac7bf6d17f8b23098c605e2f2da14909",
          "company" => "Google",
          "position" => "Software Engineer",
          "start_date" => "August 2005 ",
          "end_date" => " Present",
        ]];
        $education = [[
            "image" => "https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/10295721_10152049890301607_8853146182777969742_n.png.jpg?efg=eyJpIjoidCJ9&oh=7e9e14b98b55bfe064431ebbbfdca9e7&oe=5A5F7AB2",
            "school" => "Harvard University",
            "title" => " · ",
            "start_date" => "",
            "end_date" => "",
        ]];

        $this->assertEquals($education, $info['education']);
        $this->assertEquals('https://www.facebook.com/jenn.c.taylor', $info['profileUrl']);

    }

    /** @test */
    public function should_not_return_twitter_profile_info_with_error()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => '', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitter.com/blabla";
        $profileInfo = new TwitterProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['image']);
        $this->assertEquals("", $info['link']);
        $this->assertEquals("", $info['bio']);
    }

    /** @test */
    public function should_not_return_twitter_profile_info()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => 'Hello world'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.twitter.com/blabla";
        $profileInfo = new TwitterProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['image']);
        $this->assertEquals("", $info['link']);
        $this->assertEquals("", $info['bio']);
    }

    /** @test */
    public function should_return_f6s_profile_info()
    {
        $path = 'tests/profiles/f6a_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.f6s.com/rob";
        $profileInfo = new F6sProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
            "http://saasapi.com",
            "http://rob.bertholf.com",
            "http://bertholf.com",
            "https://www.facebook.com/RobBertholf.Hawaii",
            "https://twitter.com/@rob",
        ];
        $this->assertEquals("Rob Bertholf", $info['name']);
        $this->assertEquals("Honolulu, US", $info['location']);
        $this->assertEquals("https://s3.amazonaws.com/f6s-public/profiles/792970_th1.jpg", $info['image']);
        $this->assertEquals($links, $info['links']);
    }

    /** @test */
    public function should_not_return_f6s_profile_info()
    {
        $content = ['body' => 'content', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.f6s.com/rob";
        $profileInfo = new F6sProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['image']);
        $this->assertEquals([], $info['links']);
    }

    /** @test */
    public function should_return_angel_profile_info()
    {
        $path = 'tests/profiles/angel_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html, 'header' => ['url' => 'https://angel.co/kevin-leung']];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://angel.co/kevin-leung";
        $profileInfo = new AngelProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("Kevin Leung", $info['name']);
        $this->assertEquals("Silicon Valley", $info['location']);
        $this->assertEquals("https://d1qb2nb5cznatu.cloudfront.net/users/296481-large?1410556040", $info['image']);
        $this->assertEquals('https://angel.co/kevin-leung', $info['profile_url']);
    }

    /** @test */
    public function should_return_myspace_profile_info()
    {
        $path = 'tests/profiles/myspace_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.myspace.com/rob";
        $profileInfo = new MyspaceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
            "https://mysp.ac/3bs4"
        ];
        $this->assertEquals("Casey Fallen", $info['name']);
        $this->assertEquals("Los Angeles, CA", $info['location']);
        $this->assertEquals($links, $info['links']);
    }

    /** @test */
    public function should_not_return_myspace_profile_info()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => 'blabla'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.myspace.com/rob";
        $profileInfo = new MyspaceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals([], $info['links']);
    }

    /** @test */
    public function should_not_return_myspace_profile_info_error()
    {
        $content = ['body' => 'sasa', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.myspace.com/rob";
        $profileInfo = new MyspaceProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals([], $info['links']);
    }

    /** @test */
    public function should_return_linkedin_profile_info()
    {
        $path = 'tests/profiles/linkedin_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.linkedin.com/in/mohammedattya";
        $profileInfo = new LinkedinProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        // $this->assertEquals("Ahmed Salah", $info['name']);
        $this->assertEquals("Egypt", $info['location']);
        $this->assertEquals("https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAAfmAAAAJGFkZTQzMGZkLTVkY2MtNDRiNy1hOGQ4LTZiNWU3MDJhODhjMg.jpg", $info['image']);
        $positions = [
            [
                "company" => "Fuzzycell",
                "position" => "Web Developer",
                "startDate" => "nov 2015",
                "endDate" => "Present",
            ],
            [
                "company" => "ITI",
                "position" => "Trainee",
                "startDate" => "sep 2014",
                "endDate" => "Present",
            ]
        ];
        $education = [
            "education" => [
                [
                    "school" => "Information Technology Institute [ITI]",
                    "degree" => "Diploma In Open Source Development",
                    "startDate" => 2014,
                    "endDate" => 2015,
                ],
                [
                    "school" => "Faculty Of Computers & Information",
                    "degree" => "71 %",
                    "startDate" => 2009,
                    "endDate" => 2013,
                ],
            ],
            "age" => 26
        ];
        $this->assertEquals($positions, $info['positions']);
        $this->assertEquals($education, $info['education']);

    }

    /** @test */
    public function should_return_instagram_profile_info()
    {
        $path = 'tests/profiles/instagram_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.instagram.com/rob";
        $profileInfo = new InstagramProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Tom Higginson', $info['name']);
        $this->assertEquals('https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-19/s150x150/19428742_481390712230857_2772192181711011840_a.jpg', $info['image']);
    }

    /** @test */
    public function should_return_deviantart_profile_info()
    {
        $path = 'tests/profiles/deviantart_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://laovaan.deviantart.com/";
        $profileInfo = new DeviantartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Laovaan", $info['name']);
        $this->assertEquals("https://a.deviantart.net/avatars/l/a/laovaan.gif?6", $info['image']);
    }

    /** @test */
    public function should_not_return_deviantart_profile_info_error()
    {
        $content = ['body' => 'content', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://laovaan.deviantart.com/";
        $profileInfo = new DeviantartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_not_return_deviantart_profile_info()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => 'content'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://laovaan.deviantart.com/";
        $profileInfo = new DeviantartProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_return_github_profile_info()
    {
        $path = 'tests/profiles/github_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://github.com/taylorotwell";
        $profileInfo = new GithubProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Taylor Otwell", $info['name']);
        $this->assertEquals("Little Rock, AR", $info['location']);
        $this->assertEquals("https://avatars3.githubusercontent.com/u/463230?v=4&amp;s=460", $info['image']);
    }

    /** @test */
    public function should_return_websta_profile_info()
    {
        $path = 'tests/profiles/websta_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://websta.me/n/tom";
        $profileInfo = new WebstaProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Tom Brow", $info['name']);
    }

        /** @test */
    public function should_not_return_websta_profile_info()
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
        $url = "https://websta.me/n/tom";
        $profileInfo = new WebstaProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
    }

    /** @test */
    public function should_not_return_websta_profile_info_error()
    {
        $content = ['body' => '', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://websta.me/n/tom";
        $profileInfo = new WebstaProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
    }

    /** @test */
    public function should_return_slideshare_profile_info()
    {
        $path = 'tests/profiles/slideshare_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.slideshare.com/taylorotwell";
        $profileInfo = new SlideshareProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("John Meulemans", $info['name']);
        $links = [
            "https://twitter.com/j0hn",
            "https://www.linkedin.com/in/johnmeulemans",
        ];

        $this->assertEquals($links, $info['links']);
        $this->assertEquals("Amsterdam Area, Netherlands, Netherlands", $info['location']);
        $this->assertEquals("Founder &amp; Chief Strategy at 3sixtyfive I Influencer Agency", $info['work']);
        $this->assertEquals("http://cdn.slidesharecdn.com/profile-photo-john-96x96.jpg?cb=1497552115", $info['image']);
    }

    /** @test */
    public function should_not_return_slideshare_profile_info_error()
    {
        $content = ['body' => '', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.slideshare.com/taylorotwell";
        $profileInfo = new SlideshareProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals([], $info['links']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['work']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_not_return_slideshare_profile_info()
    {
        $content = ['body' => 'blablabla'];
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.slideshare.com/taylorotwell";
        $profileInfo = new SlideshareProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals([], $info['links']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['work']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_return_meetup_profile_info()
    {
        $path = 'tests/profiles/meetup_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.meetup.com/REAL-EGYPT-unique-tours-and-events/members/220484137/";
        $profileInfo = new MeetupProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("Karim Ali", $info['name']);
        $this->assertEquals("al-Jizah", $info['location']);
    }

    /** @test */
    public function should_not_return_meetup_profile_info_error()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);

        $content = ['body' => 'content', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.meetup.com/REAL-EGYPT-unique-tours-and-events/members/220484137/";
        $profileInfo = new MeetupProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
    }

    /** @test */
    public function should_not_return_meetup_profile_info()
    {
        $content = ['body' => 'bla bla'];
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.meetup.com/REAL-EGYPT-unique-tours-and-events/members/220484137/";
        $profileInfo = new MeetupProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
    }

    /** @test */
    public function should_return_foursquare_profile_info()
    {
        $path = 'tests/profiles/foursquare_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://foursquare.com/y_bn_ahmed";
        $profileInfo = new FoursquareProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("https://foursquare.com/y_bn_ahmed", $info['profile_url']);
        $this->assertEquals("Abu Dhabi", $info['location']);
        $this->assertEquals("Y. Ahmed", $info['name']);
        $this->assertEquals("http://twitter.com/y_bn_ahmed", $info['twitter']);
        $this->assertEquals("https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png", $info['image']);
    }

    /** @test */
    public function should_return_foursquare_api_profile_info()
    {
        $content = ['body' => '{"meta":{"code":200,"requestId":"59b02868351e3d7f13d814d5"},"notifications":[{"type":"notificationTray","item":{"unreadCount":0}}],"response":{"results":[{"id":"33","firstName":"naveen","gender":"male","photo":{"prefix":"https:\/\/igx.4sqi.net\/img\/user\/","suffix":"\/IHBVKYMA1PTMSFYR.png"},"tips":{"count":605},"lists":{"groups":[{"type":"created","count":104,"items":[]}]},"homeCity":"New York, NY","bio":"co-founder, foursquare.","contact":{"twitter":"naveen","facebook":"29103995"},"superuser":8}],"unmatched":{"twitter":[]}}}'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://api.foursquare.com/v2/users/search?twitter=naveen";
        $profileInfo = new FoursquareProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("https://api.foursquare.com/v2/users/search?twitter=naveen", $info['profile_url']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['twitter']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_return_flickr_profile_info()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $path = 'tests/profiles/flickr_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.flickr.com/people/53901376@N04/";
        $profileInfo = new FlickrProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Mark Coplan's Berkeley Public School Photos", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("https://farm5.staticflickr.com/4255/buddyicons/53901376@N04_r.jpg?1497763288#53901376@N04", $info['image']);
    }

    /** @test */
    public function should_not_return_flickr_profile_info_error()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $path = 'tests/profiles/flickr_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => 'blabla', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.flickr.com/taylorotwell";
        $profileInfo = new FlickrProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_return_vine_profile_info()
    {
        $content = ['body' => '{"code": "", "data": {"followerCount": 4787183, "remixDisabled": 0, "userId": 932909857579872256, "private": 0, "likeCount": null, "postCount": null, "explicitContent": 0, "vanityUrls": ["amandacerny"], "verified": 0, "twitterVerified": 0, "avatarUrl": "http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", "authoredPostCount": 193, "location": "Los Angeles", "username": "Amanda Cerny", "userIdStr": "932909857579872256", "description": "Instagram.com/AmandaCerny\nYoutube.com/MissAmandaCerny", "loopVelocity": null, "loopCount": 2280706219, "created": "2013-04-08T05:00:56.000000", "shareUrl": "https://vine.co/amandacerny", "profileBackground": "0x333333", "followingCount": null, "secondaryColor": "0x333333"}, "success": true, "error": ""}'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/taylorotwell";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Amanda Cerny", $info['name']);
        $this->assertEquals("Los Angeles", $info['location']);
        $this->assertEquals("https://vine.co/amandacerny", $info['profile_url']);
        $this->assertEquals("http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", $info['image']);
    }

    /** @test */
    public function should_not_return_vine_profile_info_no_username_error()
    {
        $content = ['body' => '{"code": "", "data": {"followerCount": 4787183, "remixDisabled": 0, "userId": 932909857579872256, "private": 0, "likeCount": null, "postCount": null, "explicitContent": 0, "vanityUrls": ["amandacerny"], "verified": 0, "twitterVerified": 0, "avatarUrl": "http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", "authoredPostCount": 193, "location": "Los Angeles", "username": "", "userIdStr": "932909857579872256", "description": "Instagram.com/AmandaCerny\nYoutube.com/MissAmandaCerny", "loopVelocity": null, "loopCount": 2280706219, "created": "2013-04-08T05:00:56.000000", "shareUrl": "https://vine.co/amandacerny", "profileBackground": "0x333333", "followingCount": null, "secondaryColor": "0x333333"}, "success": true, "error": ""}'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/taylorotwell";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("Los Angeles", $info['location']);
        $this->assertEquals("https://vine.co/amandacerny", $info['profile_url']);
        $this->assertEquals("http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", $info['image']);
    }

    /** @test */
    public function should_return_vine_profile_info_alt_url()
    {
        $content = ['body' => '{"code": "", "data": {"followerCount": 4787183, "remixDisabled": 0, "userId": 932909857579872256, "private": 0, "likeCount": null, "postCount": null, "explicitContent": 0, "vanityUrls": ["amandacerny"], "verified": 0, "twitterVerified": 0, "avatarUrl": "http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", "authoredPostCount": 193, "location": "Los Angeles", "username": "Amanda Cerny", "userIdStr": "932909857579872256", "description": "Instagram.com/AmandaCerny\nYoutube.com/MissAmandaCerny", "loopVelocity": null, "loopCount": 2280706219, "created": "2013-04-08T05:00:56.000000", "shareUrl": "https://vine.co/amandacerny", "profileBackground": "0x333333", "followingCount": null, "secondaryColor": "0x333333"}, "success": true, "error": ""}'];

        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/u/taylorotwell";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Amanda Cerny", $info['name']);
        $this->assertEquals("Los Angeles", $info['location']);
        $this->assertEquals("https://vine.co/amandacerny", $info['profile_url']);
        $this->assertEquals("http://v.cdn.vine.co/r/avatars/590EF68DE81169881318734368768_362cb46d1b2.1.5.jpg?versionId=eSpnRJ4RrzZ2LdGh1v0ggM8TSkR84faC", $info['image']);
    }

    /** @test */
    public function should_not_return_vine_profile_info_person_id()
    {
        config([
            'state.report_id' => 20,
            'state.combination_id' => 30,
        ]);
        $content = ['body' => '{"code": "", "data": {}, "success": true, "error": ""}'];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/taylorotwell";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['profile_url']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_not_return_vine_profile_info_error()
    {
        $content = ['body' => 'error', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/taylorotwell";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['profile_url']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_not_return_vine_profile_info_not_profile()
    {
        $content = ['body' => 'error', 'error_no' => 404];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://vine.co/";
        $profileInfo = new VineProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, $content);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['profile_url']);
        $this->assertEquals("", $info['image']);
    }

    /** @test */
    public function should_return_youtube_profile_info()
    {
        $path = 'tests/profiles/youtube_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.myspace.com/rob";
        $profileInfo = new YoutubeProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
            "http://dave2d.com",
            "http://twitter.com/Dave2Dtv",
            "http://instagram.com/Dave2Dtv",
            "http://amzn.to/2oAstOX",
        ];

        $this->assertEquals("Dave Lee", $info['name']);
        $this->assertEquals("https://yt3.ggpht.com/-ipNJcUcrKTc/AAAAAAAAAAI/AAAAAAAAAAA/PXU3bkkNT_4/s288-c-k-no-mo-rj-c0xffffff/photo.jpg", $info['image']);
        $this->assertEquals("United States", $info['location']);
        $this->assertEquals($links, $info['links']);
    }

    /** @test */
    public function should_return_googleplus_profile_info_moch()
    {
        $path = 'tests/profiles/googleplus_profile.html';
        $file = fopen($path, 'r');
        $html = fread($file, filesize($path));
        $content = ['body' => $html];
        $moch = $this->getMockBuilder(CURL::class)
            ->getMock();
        $moch->method('curl_content')
            ->willReturn($content);
        $url = "https://www.plus.google.com/12345678998745612";
        $profileInfo = new GooglePlusProfileInfo($moch);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("", $info['name']);
        $this->assertEquals("Las Vegas, NV", $info['location']);
        $this->assertEquals("https://pbs.twimg.com/profile_images/900657605697093632/5jbxJwyP_400x400.jpg", $info['image']);
        $this->assertEquals("https://3lau.com", $info['link']);
        // $this->assertEquals($html, $info['profile']);
        $this->assertEquals("the sky was quiet as we flooded its veins Snapchat: Justin3lau", $info['bio']);
    }
}
