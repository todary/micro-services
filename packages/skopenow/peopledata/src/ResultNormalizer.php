<?php

namespace Skopenow\PeopleData;

class ResultNormalizer implements ResultNormalizerInterface
{
    public function normalize(OutputModel $result, string $strategy = "")
    {
        // No more than one middle name
        if (!empty($result->other_names)) {
            $middleName = $result->middle_name;
            $acceptedOtherNames = [];
            foreach ($result->other_names as $other_name) {
                if ($other_name['first_name'] == $result->first_name && $other_name['last_name'] == $result->last_name && $result->middle_name && !$other_name['middle_name']) {
                    continue;
                }

                if (!$other_name['middle_name']) {
                    $acceptedOtherNames []= $other_name;
                    continue;
                }

                if (!$middleName) {
                    $middleName = $other_name['middle_name'];
                    $acceptedOtherNames []= $other_name;
                    continue;
                }

                if ($superSet = $this->getSupersetOf($middleName, $other_name['middle_name'])) {
                    $other_name_before = $other_name;
                    $other_name['middle_name'] = $superSet;

                    $other_name['full_name'] = $other_name['first_name'];
                    if ($other_name['middle_name']) {
                        $other_name['full_name'] .= " " . $other_name['middle_name'];
                    }
                    if ($other_name['last_name']) {
                        $other_name['full_name'] .= " " . $other_name['last_name'];
                    }

                    $acceptedOtherNames []= $other_name;
                } else if ($result->source == "merged" || $other_name['last_name']!=$result->last_name) {
                    $acceptedOtherNames []= $other_name;
                }

                foreach ($acceptedOtherNames as $k => $acceptedOtherName) {
                    if ($acceptedOtherName['first_name'] != $other_name['first_name']) {
                        continue;
                    }

                    if ($acceptedOtherName['last_name'] != $other_name['last_name']) {
                        continue;
                    }

                    if (!$acceptedOtherName['middle_name'] || !$other_name['middle_name']) {
                        continue;
                    }

                    $middleNameSuperset = $this->getSupersetOf($acceptedOtherName['middle_name'], $other_name['middle_name']);

                    if ($middleNameSuperset === false) {
                        $acceptedOtherNames[$k]['middle_name'] = "";
                    } else {
                        $acceptedOtherNames[$k]['middle_name'] = $middleNameSuperset;
                    }

                    $acceptedOtherNames[$k]['full_name'] = $acceptedOtherNames[$k]['first_name'];
                    if ($acceptedOtherNames[$k]['middle_name']) {
                        $acceptedOtherNames[$k]['full_name'] .= " " . $acceptedOtherNames[$k]['middle_name'];
                    }

                    if ($acceptedOtherNames[$k]['last_name']) {
                        $acceptedOtherNames[$k]['full_name'] .= " " . $acceptedOtherNames[$k]['last_name'];
                    }

                    continue 2;
                }
            }

            /* Remove middle name
            foreach ($acceptedOtherNames as $i => &$acceptedOtherName1) {
                foreach ($acceptedOtherNames as $j => $acceptedOtherName2) {
                    if ($acceptedOtherName1['first_name'] == $acceptedOtherName2['first_name'] && $acceptedOtherName1['last_name'] == $acceptedOtherName2['last_name'] && $acceptedOtherName2['middle_name'] && !$acceptedOtherName1['middle_name']) {
                        unset($acceptedOtherNames[$j]);
                        $acceptedOtherName1['middle_name'] = $acceptedOtherName2['middle_name'];

                        $acceptedOtherName1['full_name'] = $acceptedOtherName1['first_name'];
                        if ($acceptedOtherName1['middle_name']) {
                            $acceptedOtherName1['full_name'] .= " " . $acceptedOtherName1['middle_name'];
                        }
                        if ($acceptedOtherName1['last_name']) {
                            $acceptedOtherName1['full_name'] .= " " . $acceptedOtherName1['last_name'];
                        }

                        continue 2;
                    }
                }
            }
            */


            $result->other_names = [];
            foreach ($acceptedOtherNames as $acceptedOtherName) {
                foreach ($result->other_names as $added_other_name) {
                    if ($added_other_name['full_name'] == $acceptedOtherName['full_name']) {
                        continue 2;
                    }
                }
                $result->other_names []= $acceptedOtherName;
            }
        }

        // Do not accept relaive name like the original name
        if (!empty($result->relatives)) {
            $acceptedRelatives = [];
            foreach ($result->relatives as $relative) {
                if ($strategy == "after_merge" && !empty($relative['other_relative'])) {
                    continue;
                }

                if (mb_strlen($relative['first_name'])<3) {
                    continue;
                }
                if ($this->getSupersetOf($result->first_name, $relative['first_name'])) {
                    continue;
                }

                foreach ($acceptedRelatives as &$acceptedRelative) {
                    if ($acceptedRelative['last_name'] != $relative['last_name']) {
                        continue;
                    }

                    if ($firstNameSuperset = $this->getSupersetOf($acceptedRelative['first_name'], $relative['first_name'])) {
                        $middleNameSuperset = false;
                        if ($acceptedRelative['middle_name'] && !$relative['middle_name']) {
                            $middleNameSuperset = $acceptedRelative['middle_name'];
                        } else if (!$acceptedRelative['middle_name'] && $relative['middle_name']) {
                            $middleNameSuperset = $relative['middle_name'];
                        } else {
                            $middleNameSuperset = $this->getSupersetOf($acceptedRelative['middle_name'], $relative['middle_name']);
                        }

                        $acceptedRelative['first_name'] = $firstNameSuperset;

                        if ($middleNameSuperset === false) {
                            $acceptedRelative['middle_name'] = "";
                        } else {
                            $acceptedRelative['middle_name'] = $middleNameSuperset;
                        }

                        $acceptedRelative['full_name'] = $acceptedRelative['first_name'];
                        if ($acceptedRelative['middle_name']) {
                            $acceptedRelative['full_name'] .= " " . $acceptedRelative['middle_name'];
                        }

                        if ($acceptedRelative['last_name']) {
                            $acceptedRelative['full_name'] .= " " . $acceptedRelative['last_name'];
                        }

                        continue 2;
                    }
                }

                $acceptedRelatives[] = $relative;
            }

            $result->relatives = $acceptedRelatives;
        }

        // Remove banned profiles based on their domains
        if (!empty($result->profiles)) {
            $bannedDomains = ['whitepages.plus', 'whitepages.com'];
            $urlInfo = loadService('urlInfo');

            $normalizedProfiles = [];
            $uniqueUrls = [];
            foreach ($result->profiles as $profile) {
                if (in_array(strtolower($profile['domain']), $bannedDomains)) {
                    continue;
                }

                $normalizedUrl = $profile['url']; //$urlInfo->normalizeURL($profile['url']);
                $prepared = $urlInfo->prepareContent($normalizedUrl);
                if (in_array($prepared, $uniqueUrls)) {
                    continue;
                }

                $normalizedProfiles []= [
                    'url' => $normalizedUrl,
                    'domain' => $profile['domain'],
                ];
                $uniqueUrls []= $prepared;
            }
            $result->profiles = $normalizedProfiles;
        }

        // sort($result->phones);
        // sort($result->emails);
    }


    public function getSupersetOf(string $string1, string $string2)
    {
        if ($string1==$string2) {
            return $string1;
        }

        if (empty($string1) || empty($string2)) {
            return false;
        }

        if (mb_strlen($string1)>mb_strlen($string2) && mb_stripos($string1, $string2) === 0) {
            return $string1;
        }

        if (mb_strlen($string2)>mb_strlen($string1) && mb_stripos($string2, $string1) === 0) {
            return $string2;
        }

        return false;
    }
}
