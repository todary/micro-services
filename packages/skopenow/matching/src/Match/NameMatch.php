<?php
namespace Skopenow\Matching\Match;

use Skopenow\Matching\Interfaces\MatchInterface;

class NameMatch implements MatchInterface
{
    private $minimumPercent = 90;
    private $fn1;
    private $mn1;
    private $ln1;
    private $fn2;
    private $mn2;
    private $ln2;

    public function setFirstName1(string $fn1)
    {
        $this->fn1 = $fn1;
    }

    public function setMiddleName1(string $mn1)
    {
        $this->mn1 = $mn1;
    }

    public function setLastName1(string $ln1)
    {
        $this->ln1 = $ln1;
    }

    public function setFirstName2(string $fn2)
    {
        $this->fn2 = $fn2;
    }

    public function setMiddleName2(string $mn2)
    {
        $this->mn2 = $mn2;
    }

    public function setLastName2(string $ln2)
    {
        $this->ln2 = $ln2;
    }

    public function setMinimumPercent(int $percent)
    {
        $this->minimumPercent = $percent;
    }

    public function match()
    {
        $doesMatch = false;
        $fName = $this->fn2;
        $fName .=  !empty($this->mn2)? ' ' . $this->mn2 : null;
        $fName .= ' ' . $this->ln2;
        $fName = $this->filterName($fName);
        $search_name_arr = $this->splitName($fName);
        $this->fn2 = $search_name_arr["firstName"];
        $this->mn2 = $search_name_arr["middleName"];
        $this->ln2 = $search_name_arr["lastName"];

        // convert any dashes in name  to spaces and return full name in array ..
        $fullName = $this->clearDashesName($this->fn1, $this->mn1, $this->ln1);
        $this->fn1 = preg_replace("#[^\w\s]#u", "", $fullName['fn']);
        $this->ln1 = preg_replace("#[^\w\s\-]#u", "", $fullName['ln']);

        /**
        if (SearchApis::$testing){
            echo "<br>".$fn1 .' ' . $mn1 . ' ' . $ln1 . ', ' . $this->fn2.' ' . $this->mn2 . ' ' . $ln2 ;
        }
        */

        // Changed strtolower to mb_strtolower to converte russian capital letters
        $this->fn1 = mb_strtolower(trim($this->fn1));
        $this->ln1 = mb_strtolower(trim($this->ln1));
        $this->fn2 = mb_strtolower(trim($this->fn2));
        $this->ln2 = mb_strtolower(trim($this->ln2));

        $ln22 = '';

        $this->ln1 = str_ireplace(array('è','ê','ë','é','ç','â','à','ù','û','ü','ÿ','ô','î','ï','æ','œ'), array('e','e','e','e','c','a','a','u','u','u','y','o','i','i',"ae","ce"), $this->ln1);
        $this->ln1 = str_ireplace(array("É","È","Ê","Ë","Ç","À","Â","Ù","Û","Ü","Ÿ","Ô","Î","Ï","Æ","Œ"), array('e','e','e','e','c','a','a','u','u','u','y','o','i','i',"ae","ce"), $this->ln1);
        $this->ln2 = str_ireplace(array('è','ê','ë','é','ç','â','à','ù','û','ü','ÿ','ô','î','ï','æ','œ'), array('e','e','e','e','c','a','a','u','u','u','y','o','i','i',"ae","ce"), $this->ln2);
        $this->ln2 = str_ireplace(array("É","È","Ê","Ë","Ç","À","Â","Ù","Û","Ü","Ÿ","Ô","Î","Ï","Æ","Œ"), array('e','e','e','e','c','a','a','u','u','u','y','o','i','i',"ae","ce"), $this->ln2);

        // if last name contints an any suffix  like  Jr I II III
        if ($this->suffixLastName($this->ln2)) {
            if ($this->mn2 == $ln1) {
                $ln22 = $this->mn2 . ' ' . $this->ln2;
                $this->ln2 = $this->mn2;
            }
        }


        // this is for remove ( . ) from last name has a extra name like (jr sr ii iii iv)
        $this->ln2 = rtrim($this->ln2, '.');

        if ($this->ln1 != $this->ln2 || $this->ln1 == $ln22) {
            return array(false, 0, 'ln', null);
        }

        similar_text($this->fn1, $this->fn2, $percent1);

        if (!$this->mn1 || !$this->mn2) {
            $percent2 = 100;
        } else {
            $this->mn1 = mb_strtolower(trim($this->mn1));
            $this->mn2 = mb_strtolower(trim($this->mn2));
            if (stripos($this->mn1, $this->mn2) === 0) {
                $percent2 = 100;
            } elseif (stripos($this->mn2, $this->mn1) === 0) {
                $percent2 = 100;
            } else {
                similar_text($this->mn1, $this->mn2, $percent2);
            }
        }

        $percent3 = 0;
        $percent0 = 0;
        $percent0partial = 0;
        $percent11 = 0;
        $outBecause = '';
        if (!$this->mn1 || !$this->mn2) {
            $percent = $percent1;
            $outBecause = 'fn';
        } else {
            $percent = ($percent1 + $percent2 * 0.5) / 1.5;
            $outBecause = 'mn';
        }

        if ($percent >= $this->minimumPercent) {
            $doesMatch = true;
        }

        if (!$this->mn1 || !$this->mn2) {
            $doesMatch = false;
            $percent3 = 100;
            $percent0 = 100;
            $percent0partial = 100;
            $percent11 = 100;

            $this->fn1 = strtolower(trim($this->fn1));
            $this->fn2 = strtolower(trim($this->fn2));

            similar_text($this->fn1, $this->fn2, $percent0);

            if (strlen($this->fn1) < strlen($this->fn2)) {
                similar_text($this->fn1 . substr($this->fn2, strlen($this->fn1)), $this->fn2, $percent0partial);
            } else {
                similar_text($this->fn1, $this->fn2 . substr($this->fn1, strlen($this->fn2)), $percent0partial);
            }

            if (
                $percent0partial < 95 &&
                (strlen($this->fn1) <= 3 || strlen($this->fn2) <= 3)
            ) {
                $percent0partial = 0;
            }


            $fc1 = substr($this->fn1, 0, 1);
            $fc2 = substr($this->fn2, 0, 1);

            $this->fn1 = str_replace(array('a', 'e', 'o', 'i'), '', substr($this->fn1,1));
            $this->fn2 = str_replace(array('a', 'e', 'o', 'i'), '', substr($this->fn2,1));

            $n1 = $fc1 . substr($this->fn1, 0, min(4, strlen($this->fn1), strlen($this->fn2)));
            $n2 = $fc2 . substr($this->fn2, 0, min(4, strlen($this->fn2), strlen($this->fn2)));

            //similar_text($n1, $n2, $percent11);
            similar_text($fc1, $fc2, $percent1);
            $percent11 = 100;
            if (strlen($this->fn1) < 1 && strlen($this->fn2) < 1) {
                $percent11 = $percent0;
            } elseif (!empty($this->fn1) && !empty($this->fn2)) {
                similar_text($this->fn1, $this->fn2 . substr($this->fn1, strlen($this->fn2)), $percent11);
                if (strlen($this->fn1) < strlen($this->fn2)) {
                    similar_text($this->fn1 . substr($this->fn2, strlen($this->fn1)), $this->fn2, $percent11);
                }
            }
            //$percent = ($percent0*0.25 + $percent1 + $percent11*0.85) / 2.1;
            // TODO
            /*
            if (SearchApis::$testing) {
                echo "$percent0,$percent0partial,$percent1,$percent11";
            }
            */
            $percent = $percent0 * 0.15 + $percent0partial * 0.25 + $percent1 * 0.10 + $percent11 * 0.50;
        }

        if ($percent >= $this->minimumPercent) {
            $doesMatch = true;
        }

        $isNickname = 0;
        $isExact = 0;
        if ($doesMatch) {
            if (($percent1 + $percent11) / 2 > $percent0) {
                $isNickname = 1;
            } else {
                $isExact = 1;
            }
        }

        $nameStatus = $isExact + 2 * $isNickname; // 1 == Exact 2 = nickname 0 == nothing

        return array($doesMatch, $percent, $outBecause, $nameStatus);
    }

    private function filterName($name)
    {
        // convert \u00e9 into é related to Task#11250 .
        $name = $this->Utf8_ansi($name);
        $name = $this->ignoredName($name);

        // Remove all unicode
        $name = preg_replace("/\\\\u\\d{4}/i", "", $name);
        $name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $name);

        $name = preg_replace("#[^\w\s\-]#u", "", $name);
        $name = preg_replace("#u(\d+)#u", "", $name);
        return trim($name);
    }

    private function splitName($name)
    {
        $splittedNameIterator = loadService('nameInfo')->nameSplit(new \ArrayIterator([$name]));
        $splittedNameArray = iterator_to_array($splittedNameIterator);
        return $splittedNameArray[0]["splitted"][0]??[];
    }

    private function ignoredName($name)
    {
        $ignoredNames = [
            "\ud83c\udf38" => "",
            "ud83cudf38"   => "",
            "\ud83d\uddfc" => "",
            "ud83duddfc"   => "",
        ];
        return strtr($name, $ignoredNames);
    }

    /**
     * [Utf8_ansi convert utf8 html into ansi]
     * @param string $valor [description]
     */
    private function Utf8_ansi($valor = '')
    {
        $utf8_ansi2 = [
            "\u00c0" =>"À",
            "\u00c1" =>"Á",
            "\u00c2" =>"Â",
            "\u00c3" =>"Ã",
            "\u00c4" =>"Ä",
            "\u00c5" =>"Å",
            "\u00c6" =>"Æ",
            "\u00c7" =>"Ç",
            "\u00c8" =>"È",
            "\u00c9" =>"É",
            "\u00ca" =>"Ê",
            "\u00cb" =>"Ë",
            "\u00cc" =>"Ì",
            "\u00cd" =>"Í",
            "\u00ce" =>"Î",
            "\u00cf" =>"Ï",
            "\u00d1" =>"Ñ",
            "\u00d2" =>"Ò",
            "\u00d3" =>"Ó",
            "\u00d4" =>"Ô",
            "\u00d5" =>"Õ",
            "\u00d6" =>"Ö",
            "\u00d8" =>"Ø",
            "\u00d9" =>"Ù",
            "\u00da" =>"Ú",
            "\u00db" =>"Û",
            "\u00dc" =>"Ü",
            "\u00dd" =>"Ý",
            "\u00df" =>"ß",
            "\u00e0" =>"à",
            "\u00e1" =>"á",
            "\u00e2" =>"â",
            "\u00e3" =>"ã",
            "\u00e4" =>"ä",
            "\u00e5" =>"å",
            "\u00e6" =>"æ",
            "\u00e7" =>"ç",
            "\u00e8" =>"è",
            "\u00e9" =>"é",
            "\u00ea" =>"ê",
            "\u00eb" =>"ë",
            "\u00ec" =>"ì",
            "\u00ed" =>"í",
            "\u00ee" =>"î",
            "\u00ef" =>"ï",
            "\u00f0" =>"ð",
            "\u00f1" =>"ñ",
            "\u00f2" =>"ò",
            "\u00f3" =>"ó",
            "\u00f4" =>"ô",
            "\u00f5" =>"õ",
            "\u00f6" =>"ö",
            "\u00f8" =>"ø",
            "\u00f9" =>"ù",
            "\u00fa" =>"ú",
            "\u00fb" =>"û",
            "\u00fc" =>"ü",
            "\u00fd" =>"ý",
            "\u00ff" =>"ÿ"
        ];
        return strtr($valor, $utf8_ansi2);
    }

    /**
     * ## convert any dashes in name  to spaces and return full name in array ..
     * @param $fn
     * @param $mn
     * @param $ln
     * @return array
     */
    private function clearDashesName($fn, $mn, $ln)
    {

        $fn = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $fn);

        $mn = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $mn);

        $ln = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $ln);


        $fn = preg_replace("#[^\w\s]#u","",$fn);
        $mn = preg_replace("#[^\w\s]#u","",$mn);
        $ln = preg_replace("#[^\w\s\-]#u","",$ln);

        return array('fn' => $fn, 'mn' => $mn, 'ln' => $ln);
    }

    private function suffixLastName($lastname)
    {
        $wordsIgnored = array('ii', 'iii', 'jr', 'sr', 'iv');
        $filter = trim(strtolower(rtrim($lastname, '.')));
        return in_array($filter, $wordsIgnored);
    }

    public function checkUniqueNameComb($comb)
    {
        if (isset($comb['unique_name']) && $comb['unique_name']) {
            $combination_level = $comb['combination_level'];
            $combFields = isset($comb['combs_fields']) ? unserialize($comb['combs_fields']) : [];
            if (array_key_exists($combination_level, $combFields)) {
                $combFields = $combFields[$combination_level];
                if (is_array($combFields) and array_key_exists('uniq', $combFields)) {
                    return true;
                }
            }
        }
        return false;
    }
    // TODO
    // CHECK RETURNED VALUE
    // RETURNED VALUE ALWAYS TRUE !!!!!
    public function checkExactMatch(
        $person,
        $url,
        $title,
        $descrip,
        $additional = []
    )
    {
        $status = false;
        // $searched_names=array_filter(explode(",",$person['searched_names']));
        $analyzer = new NameAnalyzer;
        $searched_names = $analyzer->getAllPersonNames($person);
        $checkFMLName = function ($names, $body) {
            foreach ($names as $_n) {
                if (preg_match('/' . preg_quote($_n, "/") . '/i', $body)) {
                    return true;
                }
            }
            return false;
        };

        if (!empty($searched_names) && !empty($additional)) {
            $searched_names = array_merge($searched_names, $additional);
        } elseif (!empty($additional)) {
            $searched_names = $additional;
        }

        if (!empty($searched_names)) {
            // TODO
            $names = SearchApis::getPersonName($person);

            // store first_name+ +last_name .
            $arr1 = [];
            ## store first_name+last_name
            $arr2 = [];
            foreach ($searched_names as $key => $name) {
                $name = trim($name);
                $nameArr = array_filter(explode(" ", $name));
                if (!empty($nameArr)) {
                    $first_name = $nameArr[0]??"";
                    $last_name = (!empty($nameArr[2])) ? $nameArr[2] : $nameArr[1]??"";
                    $first_name = preg_quote($first_name, "/");
                    $last_name = preg_quote($last_name, "/");
                    array_push($arr1, $first_name . " " . $last_name);
                    array_push($arr2, $first_name . $last_name);
                }
            }
            $values = implode("|", $arr2);
            $re = "/\\b(" . $values . ")\\b/i";

            // check exact match in url .
            preg_match($re, $url, $match);
            if (isset($match[1]) || $checkFMLName($names['name_fml'], $url)) {
                return true;
            }
            $values = implode("|", $arr1);
            $re = "/\\b(" . $values . ")\\b/i";
            // check exact match in title and description .
            preg_match($re, $title, $match);
            if (isset($match[1]) || $checkFMLName($names['name_fml'], $title)) {
                return true;
            }

            preg_match($re,$descrip,$match);
            if (isset($match[1]) || $checkFMLName($names['name_fml'], $descrip)) {
                return true;
            }
        } else {
            return true;
        }
        return $status;
    }
}
