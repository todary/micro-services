<?php

/**
 * EntryPoint
 *
 * PHP version 7
 *
 * @package   EntryPoint
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract;

use Skopenow\Extract\Facebook\Posts\Post;
use Skopenow\Extract\Facebook\Posts\Iterator\PostIterator;
use Skopenow\Extract\Youtube\Youtube;
use Skopenow\Extract\Youtube\Iterator\YoutubeIterator;
use Skopenow\Extract\Twitter\Twitter;
use Skopenow\Extract\Twitter\Iterator\TwitterIterator;
use Skopenow\Extract\Twitter\Extractor\PostsExtractor;
use Skopenow\Extract\Twitter\Extractor\MediaExtractor;
use Skopenow\Extract\Instagram\Instagram;
use Skopenow\Extract\Instagram\Iterator\InstagramIterator;
use Skopenow\Extract\Linkedin\Linkedin;
use Skopenow\Extract\Linkedin\Iterator\LinkedinIterator;
use Skopenow\Extract\Linkedin\Extractor\SkillsExtractor;
use Skopenow\Extract\Linkedin\Extractor\SkillsEndorsersExtractor;
use Skopenow\Extract\Facebook\Images\UserImages;
use Skopenow\Extract\Facebook\Images\Iterator\ImageIterator;
use Skopenow\Extract\Facebook\Images\PageImages;

/**
 * EntryPoint Class
 *
 * EntryPoint class for extract service
 *
 * @package   EntryPoint
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   Release: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class EntryPoint
{
    
    /**
     * extractFacebookPosts
     *
     * @access public
     * @param  string $link
     * @param  string $sessId
     *
     * @return \Iterator
     */
    public function extractFacebookPosts(string $link, string $sessId = "automation_sessions_facebook", array $requestOptions = [])
    {
        $post = (new Post($link, new PostIterator()));
        $post->setSessId($sessId);
        $post->setRequestOptions($requestOptions);
        return $post->Extract()->loopResults()->getResults();
    }
    
    /**
     * extractFacebookUserImages
     *
     * @param string $link
     * @param int    $limit
     * @param mixed  $oldResult
     * @param int    $extractLevel
     * @param string $sessId
     *
     * @return \Iterator
     */
    public function extractFacebookUserImages(string $link, int $limit = 20, $oldResult = null, int $extractLevel = 0, string $sessId = "automation_sessions_facebook")
    {
        $image = (new UserImages($link, new ImageIterator()));
        $image->setSessId($sessId);
        $image->setLimit($limit);
        $image->setOldResult($oldResult);
        $image->setExtractLevel($extractLevel);
        $image->Extract();
        return $image->getResults();
    }
    
    /**
     * extractFacebookPageImages
     *
     * @param string $link
     * @param mized  $oldResult
     * @param string $sessId
     *
     * @return \Iterator
     */
    public function extractFacebookPageImages(string $link, $oldResult = null, string $sessId = "automation_sessions_facebook")
    {
        $image = (new PageImages($link, new ImageIterator()));
        $image->setSessId($sessId);
        $image->setOldResult($oldResult);
        $image->Extract();
        return $image->getResults();
    }
    
    /**
     * extractYoutubeProfiles
     *
     * @param string $profileUrl
     * @param string $type
     *
     * @return \Iterator
     */
    public function extractYoutubeProfiles(string $profileUrl, string $type = 'videos')
    {
        $youtube = (new Youtube($profileUrl, new YoutubeIterator()));
        return $youtube->Extract($type)->getResults();
    }
    
    /**
     * extractTwitterPosts
     *
     * @param string $url
     *
     * @return \Iterator
     */
    public function extractTwitterPosts(string $url) 
    {
        $twitter = (new Twitter(new TwitterIterator()));
        return $twitter->Extract(new PostsExtractor($url))->getResults();
    }
    
    /**
     * extractTwitterMedia
     *
     * @param string $url
     *
     * @return \Iterator
     */
    public function extractTwitterMedia(string $url) 
    {
        $twitter = (new Twitter(new TwitterIterator()));
        return $twitter->Extract(new MediaExtractor($url))->getResults();
    }
    
    /**
     * extractInstagramImages
     *
     * @param string $link
     * @param int    $limit
     * @param array  $oldPhotos
     *
     * @return \Iterator
     */
    public function extractInstagramImages(string $link, int $limit, array $oldPhotos = [])
    {
        $instagram = (new Instagram($link, new InstagramIterator()));
        $instagram->setLimit($limit);
        $instagram->setOldPhotos($oldPhotos);
        return $instagram->Extract()->getResults();
    }
    
    /**
     * extractLinkedinSkills
     *
     * @param string $profileId
     *
     * @return \Iterator
     */
    public function extractLinkedinSkills(string $profileId)
    {
        $linkedin = (new Linkedin(new LinkedinIterator()));
        return $linkedin->Extract(new SkillsExtractor($profileId))->getResults();
    }
    
    /**
     * extractLinkedinEndorsersUsingSkills
     *
     * @param \Iterator $skills
     *
     * @return \Iterator
     */
    public function extractLinkedinEndorsersUsingSkills(\Iterator $skills)
    {
        $linkedin = (new Linkedin(new LinkedinIterator()));
        return $linkedin->Extract(new SkillsEndorsersExtractor($skills))->getResults();
    }
}