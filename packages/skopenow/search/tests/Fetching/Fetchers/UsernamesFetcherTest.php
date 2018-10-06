<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\UsernamesFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class UsernamesFetcherTest extends \TestCase
{
    public function testFetching()
    {
        //\Cache::flush();

        setUrlMock("https://twitter.com/romado12187", file_get_contents(__DIR__ . '/../../data/Twitter-Profile-romado12187.html'));

        setUrlMock("https://www.instagram.com/romado12187", file_get_contents(__DIR__ . '/../../data/Instagram-Profile-romado12187.html'));

        setUrlMock("https://www.youtube.com/user/romado12187", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Youtube_Romado12187.html'));

        setUrlMock("https://www.etsy.com/people/romado12187", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Etsy_Romado12187.html'));

        setUrlMock("https://imgur.com/user/romado12187", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Imgur_Romado12187.html'));

        setUrlMock("http://www.instructables.com/member/romado12187/", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Instructables_Romado12187.html'));

        setUrlMock("https://www.fiverr.com/romado12187", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_fiverr_Romado12187.html'));

        setUrlMock("https://www.reddit.com/user/romado12187", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Reddit_Romado12187.html'));

        setUrlMock("https://romado12187.deviantart.com", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Deviantart_Romado12187.html'));

        setUrlMock("http://www.metacafe.com/channels/romado12187/", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Metacafe_Romado12187.html'));

        setUrlMock("https://www.stumbleupon.com/stumbler/romado12187/likes?_nospa=true", file_get_contents(__DIR__ . '/../../data/username/Username_Profile_Stumbleupon_Romado12187.html'));



        setUrlMock("https://www.pinterest.com/romado12187", '', ['HTTP/1.1 301 Redirect']);
        setUrlMock("https://myspace.com/romado12187", '', ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.facebook.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://disqus.com/by/romado12187/", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://dribbble.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://foursquare.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.tripadvisor.com/members/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://vimeo.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.dailymotion.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://soundcloud.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.quora.com/profile/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://vine.co/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://medium.com/@romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.behance.net/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.producthunt.com/@romado12187", "", ["HTTP/1.1 301 Not Found"]);
        setUrlMock("https://flipboard.com/@romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.linkedin.com/in/romado12187/", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://en.gravatar.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://keybase.io/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://www.kongregate.com/accounts/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.scribd.com/romado12187", "", ["HTTP/1.1 410 Not Found"]);
        setUrlMock("https://8tracks.com/romado12187", "This page has vanished, or perhaps it ");
        setUrlMock("https://9gag.com/u/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.drupal.org/u/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://en.wikipedia.org/wiki/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://insights.wired.com/profile/romado12187", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("http://www.ustream.tv/channel/romado12187", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("https://tunein.com/user/romado12187/", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("https://profiles.wordpress.org/romado12187", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("https://www.flickr.com/people/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://romado12187.tumblr.com", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://romado12187.livejournal.com", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://github.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://500px.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://lifestream.aol.com/stream/romado12187", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("http://romado12187.xanga.com", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("https://www.slideshare.net/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://about.me/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://www.ebay.com/usr/romado12187", '<p class="sm-md">
            The User ID you entered was not found. Please check the User ID and try again.<span class="clr">Note: you can look up a member\'s eBay User ID if you know that member\'s email address.</span></p>');
        setUrlMock("http://steamcommunity.com/id/romado12187", "<h3>The specified profile could not be found.</h3>");
        setUrlMock("https://www.last.fm/user/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://www.okcupid.com/profile/romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://bitly.com/u/romado12187", "", ["HTTP/1.1 410 Not Found"]);
        setUrlMock("http://s1257.photobucket.com/user/romado12187/profile", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://api.twitch.tv/kraken/users/romado12187?on_site=1", '', ["HTTP/1.1 400 Not Found"]);
        setUrlMock("http://hubpages.com/@romado12187", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("http://romado12187.yelp.com", "", ["HTTP/1.1 401 Not Found"]);
        setUrlMock("http://romado12187.squarespace.com", "", ["HTTP/1.1 404 Not Found"]);
        setUrlMock("https://picsart.com/romado12187", "", ["HTTP/1.1 404 Not Found"]);

        

        $criteria = new Criteria;
        $criteria->username = "romado12187";

        $fetcher = new UsernamesFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        //dd($actualList);
        // dd(count($actualList->getResults()));

        $this->assertEquals(11, count($actualList->getResults()));
    }
}
