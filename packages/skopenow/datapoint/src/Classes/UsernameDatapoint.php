<?php
/**
 * Abstract Datapoint code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint\Classes;

use Illuminate\Support\Facades\Log;

/**
 * Abstract Datapoint class
 *
 * @category Micro_Services-phase_2
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class UsernameDatapoint extends Datapoint
{
    public $isQuable = true;

    const EXCLUDED_USERNAMES = [
        'permalink.php', 'photo.php', 'search', 'me', 'info', 'help', 'admin', 'customercare', 'customer.care',
        'customer.service', 'media', 'support', 'feedback', 'noreply', 'no-reply', 'alert', 'service',
        'services', 'activation', 'online', 'status', 'security', 'stuff', 'confirm', 'contact', 'helpdesk',
        'billing', 'accounts', 'member', 'postmaster', 'root', 'policy', 'press', 'news', 'abuse', 'business',
        'legal', 'technical', 'dns', 'privacy', 'owner', 'tech', 'domain', 'registrar',
    ];

    protected function isValidUsernameStructre(&$input)
    {
        $username = $input->username;

        $status = true;
        if (!($username || (isWord($username) && empty($this->report['names']))
            || is_string($username) || strlen(trim($username)) < 3)) {
            $status = false;
        }

        if ($status) {
            $names = $this->report['names'];
            $names = names_parts($names);
            $first_name = implode('|', array_column($names, 'first_name'));
            $middle_name = implode('|', array_column($names, 'middle_name'));
            $last_name = implode('|', array_column($names, 'last_name'));

            $username = $username ?? '';

            $isMatched = false;
            if (!$isMatched && preg_match("#^(\W|_)*{$last_name}(\W|_)*{$first_name}(\W|_)*$#is", $username)) {
                $isMatched = true;
            }

            if (!$isMatched && preg_match("#^(\W|_)*{$first_name}(\W|_)*{$last_name}(\W|_)*$#is", $username)) {
                $isMatched = true;
            }

            $nicknames = loadService('reports')->getNickNames();
            if (!empty($nicknames)) {
                $nicknames = implode('|', array_column($nicknames, 'main_value'));
                if (!$isMatched && preg_match("#^(\W|_)*({$last_name})(\W|_)*({$nicknames})$#is", $username, $match)) {
                    $isMatched = true;
                }

                if (!$isMatched && preg_match("#^(\W|_)*({$nicknames})(\W|_)*({$last_name})$#is", $username, $match)) {
                    $isMatched = true;
                }
            }

            if (!$isMatched && preg_match("#^(\W|_)*{$first_name}(\W|_)*$#is", $username)) {
                $isMatched = true;
            }

            if (!$isMatched && preg_match("#^(\W|_)*" . str_replace("#", "\\#", preg_quote($username)) . "#is", $first_name)) {
                $isMatched = true;
            }

            if (!$isMatched && preg_match("#^(\W|_)*" . str_replace("#", "\\#", preg_quote($username)) . "#is", $last_name)) {
                $isMatched = true;
            }

            $status = !$isMatched;
        }

        if ($status && $input->source == "email") {
            $searchService = loadService('search');
            $criteria = new \App\Models\SearchCriteria;
            $criteria->username = $username;
            $criteria->search_type = "popularity";
            $searchList = $searchService->fetch('google', $criteria);
            $popularity = $searchList->getAvailableResultsCount();

            if ($popularity > 2099) {
                $status = false;
            } else if ($popularity > 0) {
                if ($popularity<500) {
                    $this->popularity = 'un_rare_499';
                } else if ($popularity<1000) {
                    $this->popularity = 'un_good_999';
                } else if ($popularity<1500) {
                    $this->popularity = 'un_common_1499';
                } else {
                    $this->popularity = 'un_popular_2099';
                }
            }
        }

        return $status;
    }

    protected function extractUsername($input)
    {
        $username = strtolower($input->username);
        $username = explode('/', $username)[0];
        return explode('?', $username)[0];
    }

    protected function extractNames()
    {
        $this->report['first_name'] = str_ireplace('&#039;', "'", $this->report['first_name']);
        $this->report['last_name'] = str_ireplace('&#039;', "'", $this->report['last_name']);

        $first = str_ireplace(',', '|', $this->report['first_name']);
        $last = str_ireplace(',', '|', $this->report['last_name']);
        $middle = str_ireplace(',', '|', $this->report['middle_name']);

        $first = preg_quote($first, '/');
        $last = preg_quote($last, '/');
        $middle = preg_quote($middle, '/');

        return compact('first', 'middle', 'last');
    }

    public function addEntry($input)
    {
        Log::info("add username start \n");
        $debugMsg = "[addUsername] username ($input->username) came from result ($this->resultId) .\n";

        Log::debug($debugMsg);

        if (!$this->report || !$this->isValidUsernameStructre($input)) {
            $debugMsg = "[addUsername] username ($input->username) skipped.\n";
            Log::warning($debugMsg);
            return array();
        }

        $username = $this->extractUsername($input);
        if (in_array($username, self::EXCLUDED_USERNAMES, true)) {
            $debugMsg = "[addUsername] username ($username) EXCLUDED_USERNAMES skipped.\n";
            Log::warning($debugMsg);
            return array();
        }
        // $unique_name = null;
        // $big_city = null;
        // if ($this->combination != null) {
        //     if (isset($this->combination['unique_name'])) {
        //         $unique_name = $this->combination['unique_name'];
        //     }

        //     if (isset($this->combination['big_city'])) {
        //         $big_city = $this->combination['big_city'];
        //     }
        // }

        $combs_fields = array();

        ## Disabled username score when username came from source,
        ## related with task #10361
        # un_generated is an index to make me know the username come by generate
        $combs_fields[1] = array('un' => $username);
        if (stripos($this->report['usernames'][0]??'', $username) === false) {
            $combs_fields[1]['un_generated'] = true;
        }
        $temp_sq = '"' . $username . '"';

        $generated_unames = [];
        $unames = [];

        $generated_unames[] = preg_replace('#\W|_#', '', $username); // add another username without special characters
        $generated_unames[] = preg_replace('#\W|_#', '.', $username); // add another username with dot instead of any special characters
        $generated_unames[] = preg_replace('#\W#', '_', $username); // add another username with underscore instead of any special characters

        $run = '';
        if (preg_match('#\Wsearch(\W|\$)#i', $username) == 0 && preg_match('#\Wprofile(\W|\$)#i', $username) == 0 && preg_match('#\.(php|asp|cgi)#i', $username) == 0 && preg_match('#\Wpermalink(\W|\$)#i', $username) == 0) {
            $run = $username;
        }

        $run = strtolower($run);
        if (in_array($run, $generated_unames) && strpos($run, '.')) {
            unset($generated_unames[2]);
        }

        if ($run) {
            $unames[] = $run;
        }

        /*
        $uss = array();
        $loadProgress = $this->datasource->loadProgress('added_usernames');
        if ($loadProgress && isset($loadProgress['added_usernames_data'])) {
        $added_usernames = json_decode($loadProgress['added_usernames_data'], true);
        foreach ($added_usernames as $added_username) {
        $uss[] = $added_username['username'];
        }
        }
         */

        $xdum = 0;
        $xdum2 = 0;

        $matchesPersonName = false;
        // if (!in_array($username, $uss)) {
        $combs_ids = array();

        if (!empty($this->report['first_name'])) {
            $matchesPersonName = false;
            $getNicknAmes = getPersonNickNames($this->report->id);
            $nicknames = '';
            if (!empty($getNicknAmes)) {
                $getNicknAmes = array_filter($getNicknAmes, function ($v) {
                    return preg_quote($v);
                });
                $getNicknAmes = implode('|', $getNicknAmes);
                $nicknames = $getNicknAmes;
            }
            //$debugMsg = "testing [#^(\W|_)*{$this->report['first_name']}(\W|_)*{$this->report['last_name']}(\W|_)*$#is] in $username\n";

            $name = $this->extractNames();

            $pattern = "#^(\W|_)*{$name['first']}(\W|_)*{$name['last']}(\W|_)*$#is";
            $debugMsg = "testing [$pattern] in $username\n";
            if (!$matchesPersonName && preg_match($pattern, $username)) {
                $debugMsg .= "   Failed . \n";
                $matchesPersonName = true;
            }
            Log::debug($debugMsg);

            $pattern = "#^(\W|_)*{$name['last']}(\W|_)*{$name['first']}(\W|_)*$#is";
            $debugMsg = "testing [$pattern] in $username\n";
            if (!$matchesPersonName && preg_match($pattern, $username)) {
                $debugMsg .= "   Failed . \n";
                $matchesPersonName = true;
            }
            Log::debug($debugMsg);

            if (!empty($nicknames)) {
                $pattern = "#^(\W|_)*({$name['last']})(\W|_)*({$nicknames})$#is";
                $debugMsg = "testing [$pattern] in $username\n";
                if (!$matchesPersonName && preg_match($pattern, $username/*'douglas.m.robert'*/, $match)) {
                    $debugMsg .= "   Failed . \n";
                    $matchesPersonName = true;
                }
                Log::debug($debugMsg);

                $pattern = "#^(\W|_)*({$nicknames})(\W|_)*({$name['last']})$#is";
                $debugMsg = "testing [$pattern] in $username\n";
                if (!$matchesPersonName && preg_match($pattern, $username/*'douglas.m.robert'*/, $match)) {
                    $debugMsg .= "   Failed . \n";
                    $matchesPersonName = true;
                }
                Log::debug($debugMsg);
            }

            $pattern = "#^(\W|_)*{$name['first']}(\W|_)*$#is";
            $debugMsg = "testing [$pattern] in $username\n";
            if (!$matchesPersonName && preg_match($pattern, $username)) {
                $debugMsg .= "   Failed . \n";
                $matchesPersonName = true;
            }
            Log::debug($debugMsg);

            $debugMsg = "testing [#^(\W|_)*{$username}#is] in {$name['first']}\n";
            if (!$matchesPersonName && preg_match('#^(\W|_)*' . str_replace('#', '\\#', preg_quote($username)) . '#is', $name['first'])) {
                $debugMsg .= "   Failed . \n";
                $matchesPersonName = true;
            }
            Log::debug($debugMsg);

            $debugMsg = "testing [#^(\W|_)*{$username}#is] in {$this->report['last_name']}\n";
            if (!$matchesPersonName && preg_match('#^(\W|_)*' . str_replace('#', '\\#', preg_quote($username)) . '#is', $this->report['last_name'])) {
                $debugMsg .= "   Failed . \n";
                $matchesPersonName = true;
            }
            Log::debug($debugMsg);

            // check middle name in person and alternative
            $middle_names = $this->getAllPersonNames();

            if ($this->report['middle_name'] || count($middle_names)) {
                $matchesPersonName = false;

                if (!count($middle_names)) {
                    ## stop checking with firstmiddlelast cause it is treated as primary now .
                    $pattern = "#{$name['first']}(\W|_)*{$name['middle']}(\W|_)*{$name['last']}(\W|_)*$#is";

                    $debugMsg = "testing [$pattern] in $username\n";
                    if (!$matchesPersonName && preg_match($pattern, $username)) {
                        $debugMsg .= "   Failed . \n";
                        // $matchesPersonName = true;
                    }
                    Log::debug($debugMsg);

                    $pattern = "#{$name['middle']}(\W|_)*{$name['last']}(\W|_)*$#is";
                    $debugMsg = "testing [$pattern] in $username\n";
                    if (!$matchesPersonName && preg_match($pattern, $username)) {
                        $debugMsg .= "   Failed . \n";
                        // $matchesPersonName = true;
                    }
                    Log::debug($debugMsg);

                    $pattern = "#{$name['first']}(\W|_)*{$name['middle']}(\W|_)*$#is";
                    $debugMsg = "testing [$pattern] in $username\n";
                    if (!$matchesPersonName && preg_match($pattern, $username)) {
                        $debugMsg .= "   Failed . \n";
                        // $matchesPersonName = true;
                    }
                    Log::debug($debugMsg);
                }
            } else {
                ## added by Osama
                $pattern = "#^(\W|_)*{$name['first']}(\W|_)*([a-z0-9])*(\W|_)*{$name['last']}(\W|_)*$#is";
                $debugMsg = "testing [$pattern] in $username\n";
                if (!$matchesPersonName && preg_match($pattern, $username)) {
                    $debugMsg .= "   Failed . \n";
                    $matchesPersonName = true;
                }
                Log::debug($debugMsg);
                ## // ..

                ## added by Osama
                $pattern = "#^(\W|_)*{$name['last']}(\W|_)*([a-z0-9])*(\W|_)*{$name['first']}(\W|_)*$#is";
                $debugMsg = "testing [$pattern] in $username\n";
                if (!$matchesPersonName && preg_match($pattern, $username)) {
                    $debugMsg .= "   Failed . \n";
                    $matchesPersonName = true;
                }
                Log::debug($debugMsg);
                ## // ..

                if (!empty($nicknames)) {
                    $pattern = "#^(\W|_)*{$name['last']}(\W|_)*([a-z0-9])*(\W|_)*({$nicknames})(\W|_)*$#is";
                    $debugMsg = "testing [$pattern] in $username\n";
                    if (!$matchesPersonName && preg_match($pattern, $username, $match)) {
                        $debugMsg .= "   Failed . \n";
                        $matchesPersonName = true;
                    }
                    Log::debug($debugMsg);

                    $pattern = "#^(\W|_)*({$nicknames})(\W|_)*([a-z0-9])*(\W|_)*{$name['last']}(\W|_)*$#is";
                    $debugMsg = "testing [$pattern] in $username\n";
                    if (!$matchesPersonName && preg_match($pattern, $username, $match)) {
                        $debugMsg .= "   Failed . \n";
                        $matchesPersonName = true;
                    }
                    Log::debug($debugMsg);
                }

                ## the process below related with task #10451
                if ($unique_name) {
                    if ($matchesPersonName) {
                        if (preg_match("#^{$this->report['first_name']}[a-z-._]+{$this->report['last_name']}$#is", $username, $match)) {
                            $matchesPersonName = false;
                        } else if (preg_match("#^{$this->report['last_name']}[a-z-._]+{$this->report['first_name']}$#is", $username, $match)) {
                            $matchesPersonName = false;
                        }
                    }
                    $debugMsg = "extract this username ( $username ) reason: match with name+middle+lastname and came from unique name \n";

                    Log::debug($debugMsg);
                }
                ## // ..
            }

            if ($matchesPersonName) {
                $debugMsg = "Ignoring username ( $username ) reason: matched with the person name \n";
                Log::debug($debugMsg);

                return array();
            }

            $newCombId = 0;
            if (!$matchesPersonName) {
                $debugMsg = "Person: {$this->report->id}, {$username} Does not match any combination of the person name\n";
                // logging:: $this->report->id, $debugMsg, $this->combination);
                if ($this->checkEmail($this->report->id, $username, $this->report->first_name, $this->report->last_name)) {
                    $debugMsg = "Person: {$this->report->id}, {$username} Store username combinations\n";
                    // logging:: $this->report->id, $debugMsg, $this->combination);
                    $additionalData = '';
                    if ($this->source) {
                        $additionalData = json_encode(['source' => strtolower($this->source)]);
                    }

                    $xdum = false;
                    if (!$this->skipGoogleUsername) {
                        // $xdum = $this->combinationsService->store('google_usernames', [
                        //     'unique_name' => $unique_name,
                        //     'big_city' => $big_city,
                        //     'additional' => $additionalData,
                        //     'extra_data' => $this->extraData,
                        // ], $this->combination->id);
                        // $this->combinationsService->addCombinationLevel($xdum, $temp_sq, $combs_fields[1]);

                        // $xdum = SearchApis::store_combination(
                        //     $unique_name, $big_city, 'google_usernames',
                        //     $temp_sq, null, null,
                        //     $combs_fields, $this->report, false,
                        //     (($this->is_generated) ? 'is_generated_' : '') . $username, $this->combination,
                        //     $newCombId, $additionalData, $this->extraData
                        // );
                    }

                    // $xdum2 = $this->combinationsService->store('usernames', [
                    //     'unique_name' => $unique_name,
                    //     'big_city' => $big_city,
                    //     'additional' => '',
                    //     'extra_data' => $this->extraData,
                    // ], $this->combination->id);
                    // $this->combinationsService->addCombinationLevel($xdum2, $temp_sq, $combs_fields[1]);

                    // $xdum2 = SearchApis::store_combination(
                    //     $unique_name, $big_city, 'usernames',
                    //     $temp_sq, null, null,
                    //     $combs_fields, $this->report, false,
                    //     (($this->is_generated) ? 'is_generated_' : '') . '', $this->combination,
                    //     $newCombId, '', $this->extraData);
                    if (!empty($generated_unames)) {
                        foreach ($generated_unames as $run) {
                            if (!$run) {
                                continue;
                            }

                            if (is_numeric(preg_replace('#\W#', '', $run))) {
                                continue;
                            }

                            if (in_array($run, $unames)) {
                                continue;
                            }
                            //TODO:: Urgant
                            /*$input->username = $run;
                            $this->addEntry();*/
                            // SearchApis::add_username($run, $this->report, $this->combination, $this->result_id, $this->is_generated, $this->source, $this->extraData, $this->skipGoogleUsername);
                        }
                    }
                } else {
                    $debugMsg = "Person: {$this->report->id}, {$username} Does not pass from the check email condition. \n";
                    // logging:: $this->report->id, $debugMsg, $this->combination);
                }
            } else {
                $debugMsg = "Person: {$this->report->id}, Ignored username: $username as it matches a combination of person name\n";
                // logging:: $this->report->id, $debugMsg, $this->combination);
            }
        } else {
            /*if ($this->checkEmail($username)) {
            $additionalData = '';
            if ($this->source) {
            $additionalData = json_encode(['source' => strtolower($this->source)]);
            }

            $xdum = false;
            if (!$this->skipGoogleUsername) {
            // $xdum = $this->combinationsService->store('google_usernames', [
            //     'unique_name' => $unique_name,
            //     'big_city' => $big_city,
            //     'additional' => $additionalData,
            //     'extra_data' => $this->extraData,
            // ], $this->combination->id);
            // $this->combinationsService->addCombinationLevel($xdum, $temp_sq, $combs_fields[1]);
            // $xdum = SearchApis::store_combination(
            //     $unique_name, $big_city, 'google_usernames',
            //     $temp_sq, null, null,
            //     $combs_fields, $this->report, false,
            //     (($this->is_generated) ? 'is_generated_' : '') . $username, $this->combination,
            //     $newCombId, $additionalData, $this->extraData);
            }

            // $xdum2 = $this->combinationsService->store('google_usernames', [
            //     'unique_name' => $unique_name,
            //     'big_city' => $big_city,
            //     'additional' => '',
            //     'extra_data' => $this->extraData,
            // ], $this->combination->id);
            // $this->combinationsService->addCombinationLevel($xdum2, $temp_sq, $combs_fields[1]);

            // $xdum2 = SearchApis::store_combination(
            //     $unique_name, $big_city, 'usernames',
            //     $temp_sq, null, null,
            //     $combs_fields, $this->report, false,
            //     (($this->is_generated) ? 'is_generated_' : '') . '', $this->combination,
            //     $newCombId, '', $this->extraData);
            if (!empty($generated_unames)) {
            foreach ($generated_unames as $run) {
            if (!$run) {
            continue;
            }

            if (is_numeric(preg_replace('#\W#', '', $run))) {
            continue;
            }

            if (in_array($run, $unames)) {
            continue;
            }

            //TODO:: Urgant
            // $input->username = $run;
            // $this->addEntry();
            // SearchApis::add_username($run, $this->report, $this->combination, $this->result_id, $this->is_generated, $this->source, $this->extraData, $this->skipGoogleUsername);
            }
            }
            }
            $debugMsg = "Person: {$this->report->id}, Not google \n";*/
            // logging:: $this->report->id, $debugMsg, $this->combination);
        }

        $this->datasource->updateProgress('usernames', 1, false);


        $data = [
            'key' => md5($input->username),
            'username' => $input->username,
            'assoc_profile' => $this->resultId ? "res_$this->resultId" : ($this->combinationId ? "comb_$this->combinationId" : 'comb_base'),
            'parent_comb' => $this->combinationId,
            'res' => $this->resultId,
            'combinations_ids' => $combs_ids,
            'popularity' => $this->popularity??null,
        ];

        $this->addDataPoint('added_usernames', $data, $input);
        /*SearchApis::progress_data("added_usernames", array(
        "key" => md5($username),
        "username" => $username,
        "assoc_profile" => (($result_id) ? "res_" . $result_id : ((!empty($combination['id'])) ? "comb_" . $combination['id'] : "comb_base")),
        "parent_comb" => $combinationId,
        "res" => $result_id,
        "combinations_ids" => $combs_ids,

        ));*/
        // if ($xdum) {
        //     $combs_ids[] = $xdum;
        // }

        // if ($xdum2) {
        //     $combs_ids[] = $xdum2;
        // }

        // $combinationId = ($this->combination && isset($this->combination['combination_id'])) ? $this->combination['combination_id'] : null;
        // if (is_null($combinationId) && $this->combination) {
        //     $combinationId = $this->combination['id'];
        // }

        /*// logging:: $this->report, "added_usernames", "added_usernames_data", array(
        "key" => md5($username),
        "username" => $username,
        "assoc_profile" => (($this->result_id) ? "res_" . $this->result_id : ((!empty($this->combination['id'])) ? "comb_" . $this->combination['id'] : "comb_base")),
        "parent_comb" => $combinationId,
        "res" => $this->result_id,
        "combinations_ids" => $combs_ids,

        ), $this->combination);*/
        // }
        return array($xdum, $xdum2);
    }

    public function getAllPersonNames()
    {
        $searched_names = $this->datasource->currentProgress('added_usernames', false);
        // $searched_names = $temp['names_data'];
        // $searched_names = json_decode($searched_names, true);
        $searched_names = @array_column($searched_names, 'name');
        if (empty($searched_names)) {
            $searched_names = array_filter(explode(',', $this->report['searched_names']));
        }

        if (!empty($searched_names)) {
            $searched_names = array_unique($searched_names);
        }
        $names_temp = explode(',', $this->report['searched_names']);
        if (!empty($names_temp)) {
            $searched_names = array_unique(array_map('StrToLower', array_merge($searched_names, $names_temp)));
        }

        return array_filter(array_map(function ($value) {
            $names = explode(' ', $value);
            if (count($names) == 3) {
                return $value;
            }
        }, $searched_names));
    }
}
