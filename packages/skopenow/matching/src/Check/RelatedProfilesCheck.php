<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;

class RelatedProfilesCheck implements CheckInterface
{
    private $status = [
        'name' => 0,
        'location' => 0,
        'bestLocationDetails' => [],
        'bestNameDetails' => [],
        'found_facebook_location' => 0,
        'found_linkedin_location' => 0,
        'found_twitter_location' => 0,
        'additionalProfiles' => [],
    ];

    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];
    private $links;

    public function __construct(
        string $url,
        array $info,
        $combination,
        ReportService $report
    )
    {
        $this->entry = loadService('urlInfo');
        $this->data = (new Status)->matchingData;
        $this->combination = $combination;
        $this->person = $report->getReport();
        $this->url = $url;
        $this->info = $info;
        $this->report = $report;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    public function check()
    {
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $links = $this->links;
        $profile_url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        if (!is_array($links) || count($links) == 0) {
            return $this->data;
        }
        $bestLocationDetails = [];
        $bestNameDetails = [];
        foreach ($links as $link) {
            $siteName = strtolower($this->entry->getSiteName($link));

            switch ($siteName) {
                case 'facebook':
                    return $this->checkFacebook($link, $person, $combination);
                    break;
                case 'linkedin':
                    return $this->checkLinkedin($link, $person, $combination);
                    break;
                case 'twitter':
                        $t = microtime(true);
                        // SearchApis::logData ( $person ['id'], "Found search {$link} to check pinterest profile $profile_url", $combination );
                        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : logData'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
                        // add report log
                        $res_array = array (
                                "source" => "twitter(pinterest)",
                                "main_source" => "twitter",
                                "type" => 'result',
                                "content" => $link,
                                "combination_id" => $combination ['id']
                        );
                        $t = microtime(true);
                        // Yii::app ()->reportLog->resultFound ( $res_array, $person, $combination );
                        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : reportLog->resultFound'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
                        unset($res_array);
                        $t = microtime(true);
                        $info = $this->entry->getProfileInfo($link, 'twitter');
                        $check = new TwitterCheck($link, $info, $person, $combination);
                        return $check->check();
                    break;
                case 'youtube':
                    $info = $this->entry->getProfileInfo($link, 'youtube');
                    $check = new YoutubeCheck($link, $info, $person, $combination);
                    return $check->check();
                    break;
            }
        }
        return $this->data;
    }

    private function checkFacebook($link, $person, $combination)
    {
        // TODO
        // SearchApis::logData ( $person ['id'], "Found search {$link} to check facebook profile $profile_url", $combination );
        $res_array = [
            "source" => "facebook(googleplus)",
            "main_source" => "facebook",
            "type" => 'result',
            "content" => $link,
            "combination_id" => $combination['id']
        ];
        $t = microtime(true);
        // TODO
        // Yii::app ()->reportLog->resultFound ( $res_array, $person, $combination );
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : reportLog->resultFound'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
        unset($res_array);
        $t = microtime(true);
        $info = $this->entry->getProfileInfo($link, 'facebook');
        $check = new FacebookCheck($link, $info, $person, $combination);
        return $check->check();
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : check_facebook'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};

    }

    private function checkLinkedin($link, $person, $combination)
    {
        $t = microtime(true);
        // TODO
        // SearchApis::logData ( $person ['id'], "Found search {$link} to check pinterest profile $profile_url", $combination );
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : logData'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
        // add report log

        $res_array = [
            "source" => "linkedin(pinterest)",
            "main_source" => "linkedin",
            "type" => 'result',
            "content" => $link,
            "combination_id" => $combination ['id']
        ];

        $t = microtime(true);

        // TODO
        // Yii::app ()->reportLog->resultFound ( $res_array, $person, $combination );
        // if (get_class(Yii::app())=="CConsoleApplication" and Yii::app()->params['runCommandLog']){global $_start_time; echo number_format(microtime(true)-$_start_time,3) . " " . 'Class : '.__CLASS__.', Function : '.__METHOD__.', Mission : reportLog->resultFound'.' Time : '.number_format(microtime(true)-$t,3).', File : '.__FILE__.', Line : '.__LINE__."<br>\n";debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);echo "\n\n";};
        unset($res_array);
        $info = $this->entry->getProfileInfo($link, 'linkedin');
        $check = new LinkedinCheck($link, $info, $person, $combination);
        return $check->check();
    }
}
