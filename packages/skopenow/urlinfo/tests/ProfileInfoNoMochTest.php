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


class ProfileInfoNoMochTest extends \TestCase
{
    /** @test */
    public function should_return_pinterest_profile_info_no_moch()
    {
        $url = "https://www.pinterest.co.uk/jennifer/";
        $profileInfo = new PinterestProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Jenn Low", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("", $info['twitter']);
        $this->assertEquals("Founder of Wanderlust + Co // jewels for dreamers x lovers // a glimpse into the W+Co world... live beautifully x", $info['bio']);
        $this->assertEquals('https://s-media-cache-ak0.pinimg.com/avatars/jennifer_1405690691_140.jpg', $info['image']);
    }

    /** @test */
    public function should_return_twitter_profile_info_no_moch()
    {
        $url = "https://www.twitter.com/Justin";
        $profileInfo = new TwitterProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Justin Williams", $info['name']);
        $this->assertEquals("Denver, CO", $info['location']);
        $this->assertEquals('https://pbs.twimg.com/profile_images/874635638284230656/q6I4XdRA_400x400.jpg', $info['image']);
        $this->assertEquals("https://justinw.me", $info['link']);
        $this->assertEquals("semi-retired.", $info['bio']);
    }

    /** @test */
    public function should_return_facebook_profile_info_no_moch()
    {
        $url = "fb.me/jenn.c.taylor";
        $profileInfo = new FacebookProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $experience = [[
            "image" => "https://scontent-yyz1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/13510840_10154299187657838_4218160330552521709_n.png.jpg?efg=eyJpIjoidCJ9&oh=8d25433087e41f095c88cd1fc6f76908&oe=5A4D916B",
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

        $this->assertEquals("Jennifer Chen Taylor", $info['name']);
        $this->assertEquals([], $info['location']);
        $this->assertEquals('https://www.facebook.com/jenn.c.taylor', $info['profileUrl']);
        // image server changes every request

        // $this->assertEquals("https://scontent-yyz1-1.xx.fbcdn.net/v/t1.0-1/cp0/e15/q65/p48x48/13510840_10154299187657838_4218160330552521709_n.png.jpg?efg=eyJpIjoidCJ9&oh=8d25433087e41f095c88cd1fc6f76908&oe=5A4D916B", $info['image']);
        // $this->assertEquals($education, $info['education']);
        // $this->assertEquals($experience, $info['experience']);

    }


    /** @test */
    public function should_return_f6s_profile_info_no_moch()
    {
        $url = "https://www.f6s.com/rob";
        $profileInfo = new F6sProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
            "http://saasapi.com",
            "http://rob.bertholf.com",
            "http://bertholf.com",
            'http://robbertholf.com',
            "https://www.facebook.com/RobBertholf.Hawaii",
            "https://twitter.com/@rob",
        ];
        $this->assertEquals("Rob Bertholf", $info['name']);
        $this->assertEquals("Honolulu, US", $info['location']);
        $this->assertEquals("https://s3.amazonaws.com/f6s-public/profiles/792970_th1.jpg", $info['image']);
        $this->assertEquals($links, $info['links']);
    }

    /** @test */
    public function should_return_angel_profile_info_no_moch()
    {
        $url = "https://angel.co/kevin-leung";
        $profileInfo = new AngelProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("Kevin Leung", $info['name']);
        $this->assertEquals("Silicon Valley", $info['location']);
        $this->assertEquals("https://d1qb2nb5cznatu.cloudfront.net/users/296481-large?1410556040", $info['image']);
        $this->assertEquals('https://angel.co/kevin-leung', $info['profile_url']);
    }

    /** @test */
    public function should_return_myspace_profile_info_no_moch()
    {
        $url = "https://www.myspace.com/rob";
        $profileInfo = new MyspaceProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
        ];
        $this->assertEquals("Rob Boothroyd", $info['name']);
        $this->assertEquals("Cardiff, Wales, United Kingdom", $info['location']);
        $this->assertEquals($links, $info['links']);
    }


    /** @test */
    public function should_return_linkedin_profile_info_no_moch()
    {
        $url = "https://www.linkedin.com/in/mohammedattya";
        $profileInfo = new LinkedinProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);

        // $this->assertEquals("Ahmed Salah", $info['name']);
        $this->assertEquals("Egypt", $info['location']);
        $this->assertEquals("https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAALDAAAAJDQ0MDE3MDhlLWEzMDgtNGEyNy05YTFkLWQyNDA0NGE0MDEzYw.jpg", $info['image']);
        $positions = [
            [
                "company" => "Moselay Media Development",
                "position" => "Intern",
                "startDate" => "mar 2017",
                "endDate" => "jul 2017",
            ],
            [
                "company" => "Faculty of Engineering Menofia university",
                "position" => "Student",
                "startDate" => "sep 2009",
                "endDate" => "aug 2015",
            ],
            [
                'company' => 'Queen Tech Solutions',
                'position' => 'PHP Web Developer',
                'startDate' => 'aug 2017',
                'endDate' => 'Present',
            ],
            [
                'company' => 'Egyptian Army',
                'position' => 'Soldier',
                'startDate' => 'jan 2016',
                'endDate' => 'mar 2017',
            ]
        ];
        $education = [
            "education" => [
                [
                    "school" => "Arab Elraml secondary school",
                    "degree" => "Secondary School",
                    "startDate" => 2006,
                    "endDate" => 2009,
                ],
                [
                    "school" => "Faculty of Engineering Menofia university",
                    "degree" => "student",
                    "startDate" => 2009,
                    "endDate" => 2015,
                ],
            ],
            "age" => 29
        ];
        $this->assertEquals($positions, $info['positions']);
        $this->assertEquals($education, $info['education']);

    }

    /** @test */
    public function should_return_instagram_profile_info_no_moch()
    {
        $url = "https://www.instagram.com/higgypop/";
        $profileInfo = new InstagramProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals('Tom Higgenson', $info['name']);
        $this->assertEquals('https://ig-s-c-a.akamaihd.net/h-ak-igx/t51.2885-19/s150x150/20065761_1213026505491734_4079462471495057408_a.jpg', $info['image']);
    }

    /** @test */
    public function should_return_deviantart_profile_info_no_moch()
    {
        $url = "https://laovaan.deviantart.com/";
        $profileInfo = new DeviantartProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Laovaan", $info['name']);
        $this->assertEquals("https://a.deviantart.net/avatars/l/a/laovaan.gif?6", $info['image']);
    }


    /** @test */
    public function should_return_github_profile_info_no_moch()
    {
        $url = "https://github.com/taylorotwell";
        $profileInfo = new GithubProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Taylor Otwell", $info['name']);
        $this->assertEquals("Little Rock, AR", $info['location']);
        $this->assertEquals("https://avatars3.githubusercontent.com/u/463230?v=4&amp;s=460", $info['image']);
    }

    /** @test */
    public function should_return_websta_profile_info_no_moch()
    {
        $url = "https://websta.me/n/tom";
        $profileInfo = new WebstaProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Tom Brow", $info['name']);
    }


    /** @test */
    public function should_return_slideshare_profile_info_no_moch()
    {
        $url = "https://www.slideshare.net/john";
        $profileInfo = new SlideshareProfileInfo(new CURL);
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
    public function should_return_meetup_profile_info_no_moch()
    {
        $url = "https://www.meetup.com/members/220484137/";
        $profileInfo = new MeetupProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("Mai Mohmmed S.", $info['name']);
        $this->assertEquals("al-Jizah", $info['location']);
    }


    /** @test */
    public function should_return_foursquare_profile_info_no_moch()
    {
        $url = "https://foursquare.com/y_bn_ahmed";
        $profileInfo = new FoursquareProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("https://foursquare.com/y_bn_ahmed", $info['profile_url']);
        $this->assertEquals("Abu Dhabi", $info['location']);
        $this->assertEquals("Y. Ahmed", $info['name']);
        $this->assertEquals("http://twitter.com/y_bn_ahmed", $info['twitter']);
        $this->assertEquals("https://ss0.4sqi.net/img/footer-top@2x-ef6ccfa1b4ce50e9257b922d1c8935ac.png", $info['image']);
    }

    /** @test */
    public function should_return_foursquare_profile_info_no_moch_api()
    {
        $url = "https://api.foursquare.com/v2/users/87722883?oauth_token=DEGLXYZHI3F2A4TLBIIV4TYFZHGHN0RTHNFV4RCGNL2OOJNV&v=20160726";
        $profileInfo = new FoursquareProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);

        $this->assertEquals("Rob Douglas", $info['name']);
        $this->assertEquals("Burlington, VT", $info['location']);
        $this->assertEquals("", $info['twitter']);
        $this->assertEquals("1115205832", $info['facebook']);
        $this->assertEquals('https://igx.4sqi.net/img/user/130x130/87722883-ON4VTVTEO2RQSEOP.jpg', $info['image']);
        $this->assertEquals("https://foursquare.com/user/87722883", $info['profile_url']);
    }


    /** @test */
    public function should_return_flickr_profile_info_no_moch()
    {
        $url = "https://www.flickr.com/people/53901376@N04/";
        $profileInfo = new FlickrProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("Mark Coplan's Berkeley Public School Photos", $info['name']);
        $this->assertEquals("", $info['location']);
        $this->assertEquals("https://farm5.staticflickr.com/4255/buddyicons/53901376@N04_r.jpg?1497763288#53901376@N04", $info['image']);
    }


    /** @test */
    public function should_return_vine_profile_info_no_moch()
    {
        $url = "https://vine.co/GeorgeJanko";
        $profileInfo = new VineProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $this->assertEquals("George Janko", $info['name']);
        $this->assertEquals("Hollywood", $info['location']);
        $this->assertEquals("https://vine.co/georgejanko", $info['profile_url']);
        $this->assertEquals("http://v.cdn.vine.co/r/avatars/90BCC3A66C1274995484448399360_47f11d83754.3.0.jpg?versionId=927983hO.FmAAIsAGXE51YnnETuDPRXz", $info['image']);
    }

    /** @test */
    public function should_return_youtube_profile_info_no_moch()
    {
        $url = "https://www.youtube.com/channel/UCVYamHliCI9rw1tHR1xbkfw";
        $profileInfo = new YoutubeProfileInfo(new CURL);
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
    public function should_return_googleplus_profile_info_no_moch()
    {
        $url = "https://plus.google.com/112414462117529518114";
        $profileInfo = new GooglePlusProfileInfo(new CURL);
        $info = $profileInfo->getProfileInfo($url, []);
        $links = [
            "http://www.google.com/reader/shared/17537142613061582294",
            "http://www.google.com/reader/shared/maruawe",
            "http://twitter.com/maruawe",
            "http://pulse.yahoo.com/_DISMKWQABSNUXQAZAFCGXA3AC4",
            "https://picasaweb.google.com/112414462117529518114",
        ];
        $this->assertEquals("William Johnston", $info['name']);
        $location = [
            'San Antonio Texas',
            'San Antonio Texas',
            'Silverton Colorado',
        ];
        $education = [
            [
              "school" => "Harlandale HIgh",
              "degree" => "",
              "startDate" => "1957",
              "endDate" => "1961",
            ],
            [
              "school" => "NMSU",
              "degree" => "art",
              "startDate" => "1970",
              "endDate" => "1973",
            ]
        ];
        $work = [
            [
                "company" => "Bwkgraphics",
                "position" => "Retired",
                "startDate" => "",
                "endDate" => "2011",
            ]
        ];
        $this->assertEquals($location, $info['location']);
        $this->assertEquals("https://lh6.googleusercontent.com/-LwuD6nEKOcE/AAAAAAAAAAI/AAAAAAAAB3s/RZecXlbsByo/photo.jpg", $info['image']);
        $this->assertEquals($links, $info['links']);
        $this->assertEquals($education, $info['education']);
        $this->assertEquals($work, $info['work']);
    }
}
