<?php

namespace Skopenow\UrlInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UrlInfoController extends Controller
{
    /**
     * @param Request $request
     */
    public function profileExists(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $pattern = $request->input('data.pattern');
        $output = $entryPoint->profileExists($url, $pattern);
        response()->json(['profileExists' => $output]);
    }

    /**
     * @param Request $request
     */
    public function isProfile(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->isProfile($url);
        response()->json(['isProfile' => $output]);
    }

    /**
     * @param Request $request
     */
    public function profileImage(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->getProfileImage($url);
        response()->json(['profileImage' => $output]);
    }

    /**
     * @param Request $request
     */
    public function prune(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->prepareContent($url);
        response()->json(['url' => $output]);
    }

    /**
     * @param Request $request
     */
    public function source(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $defaultSource = $request->input('data.defaultSource');
        $defaultMainSource = $request->input('data.defaultMainSource');
        $sourceSuffix = $request->input('data.sourceSuffix');
        $output = $entryPoint->determineSource($url, $sourceSuffix, $defaultSource, $defaultMainSource);
        response()->json(['source' => $output[0], 'mainSource' => $output[1]]);
    }

    /**
     * @param Request $request
     */
    public function urlNormalizer(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->normalizeURL($url);
        response()->json(['normalizedUrl' => $output]);
    }

    /**
     * @param Request $request
     */
    public function username(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->getProfileImage($url);
        response()->json(['username' => $output]);
    }

    /**
     * @param Request $request
     */
    public function siteTag(Request $request)
    {
        $entryPoint = new EntryPoint;
        $url = $request->input('data.url');
        $output = $entryPoint->getSiteTag($url);
        response()->json(['profileImage' => $output]);
    }
}
