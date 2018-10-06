<?php

/**
 * ExtractController
 *
 * @package   ExtractController
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */
namespace Skopenow\Extract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Skopenow\Extract\EntryPoint;

/**
 * ExtractController
 *
 * @package   ExtractController
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class ExtractController extends Controller
{
    
    /**
     * extractFacebookPosts
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractFacebookPosts(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $out = $entryPoint->extractFacebookPosts($link, ($sessId??"automation_sessions_facebook"));
        
        echo json_encode($out, true);
    }
    
    /**
     * extractFacebookUserImages
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractFacebookUserImages(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $limit = $limit??20;
        $oldResult = $oldResult??null;
        $extractLevel = $extractLevel??0;
        $sessId = $sessId??"automation_sessions_facebook";
        $out = $entryPoint->extractFacebookUserImages($link, $limit, $oldResult, $extractLevel, $sessId);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractFacebookPageImages
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractFacebookPageImages(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $oldResult = $oldResult??null;
        $sessId = $sessId??"automation_sessions_facebook";
        $out = $entryPoint->extractFacebookPageImages($link, $oldResult, $sessId);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractYoutubeProfiles
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractYoutubeProfiles(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $type = $type??'videos';
        $out = $entryPoint->extractYoutubeProfiles($profileUrl, $type);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractTwitterPosts
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractTwitterPosts(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $out = $entryPoint->extractTwitterPosts($url);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractTwitterMedia
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractTwitterMedia(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $out = $entryPoint->extractTwitterMedia($url);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractInstagramImages
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractInstagramImages(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $oldResult = $oldResult??array();
        $out = $entryPoint->extractInstagramImages($link, $limit, $oldResult);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractLinkedinSkills
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractLinkedinSkills(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $out = $entryPoint->extractLinkedinSkills($profileId);
        
        echo json_encode($out, true);
    }
    
    /**
     * extractLinkedinEndorsersUsingSkills
     *
     * @param Request $request
     * 
     * @return string
     */
    public function extractLinkedinEndorsersUsingSkills(Request $request)
    {
        $entryPoint = new EntryPoint();
        $inputs = $request->all();
        extract($inputs);
        $out = $entryPoint->extractLinkedinEndorsersUsingSkills(new \ArrayIterator($skills));
        
        echo json_encode($out, true);
    }
}
